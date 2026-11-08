<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Services\SupabaseStorageService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProductManagementController extends Controller
{
    protected SupabaseStorageService $storage;

    public function __construct(SupabaseStorageService $storage)
    {
        $this->storage = $storage;
    }
    public function index()
    {
        $products = Product::with(['category', 'variations'])->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            // Variations (sizes and stock)
            'variations' => 'nullable|array',
            'variations.*.size' => 'required|string|max:50',
            'variations.*.stock_quantity' => 'required|integer|min:0',
        ]);

        // Product images must be stored in Supabase; do not silently fall back to
        // Railway's ephemeral local filesystem.
        $bucket = config('services.supabase.buckets.products', 'product-images');
        $uploadResult = $this->storage->upload($request->file('image'), $bucket, 'products');

        if (!$uploadResult['success']) {
            Log::error('Product image upload to Supabase failed', [
                'status_or_error' => $uploadResult['error'],
            ]);

            throw ValidationException::withMessages([
                'image' => 'Foto gagal diunggah ke penyimpanan. Silakan coba lagi atau hubungi administrator.',
            ]);
        }

        // Store the storage path, not a Railway URL. Product::image accessor
        // converts this path to the public Supabase URL when rendering.
        $validated['image'] = $uploadResult['path'];

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $product = Product::create($validated);

        // Create product variations if provided
        if ($request->has('variations') && is_array($request->variations)) {
            foreach ($request->variations as $variation) {
                if (!empty($variation['size']) && isset($variation['stock_quantity'])) {
                    $product->variations()->create([
                        'size' => $variation['size'],
                        'stock_quantity' => $variation['stock_quantity'],
                    ]);
                }
            }
        } else {
            // Create default "One Size" variation if no variations provided
            $product->variations()->create([
                'size' => 'One Size',
                'stock_quantity' => 0,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(string $id)
    {
        $product = Product::with('variations')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            // Variations
            'variations' => 'nullable|array',
            'variations.*.id' => 'nullable|exists:product_variations,id',
            'variations.*.size' => 'required|string|max:50',
            'variations.*.stock_quantity' => 'required|integer|min:0',
        ]);

        // Upload a replacement first. Keep the old image intact if upload fails.
        $oldImageRaw = $product->getRawOriginal('image');
        $newUploadedPath = null;

        if ($request->hasFile('image')) {
            $bucket = config('services.supabase.buckets.products', 'product-images');
            $uploadResult = $this->storage->upload($request->file('image'), $bucket, 'products');

            if (!$uploadResult['success']) {
                Log::error('Product image replacement upload failed', [
                    'product_id' => $product->id,
                    'status_or_error' => $uploadResult['error'],
                ]);

                throw ValidationException::withMessages([
                    'image' => 'Foto baru gagal diunggah. Foto lama tetap dipertahankan.',
                ]);
            }

            $validated['image'] = $uploadResult['path'];
            $newUploadedPath = $uploadResult['path'];
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        // Delete the previous Supabase object only after the database update succeeds.
        if ($newUploadedPath && $oldImageRaw) {
            $oldStoragePath = $this->extractProductStoragePath($oldImageRaw);
            if ($oldStoragePath && $oldStoragePath !== $newUploadedPath) {
                $this->storage->delete(
                    config('services.supabase.buckets.products', 'product-images'),
                    $oldStoragePath
                );
            }
        }

        // Update variations
        if ($request->has('variations') && is_array($request->variations)) {
            $existingVariationIds = [];
            
            foreach ($request->variations as $variationData) {
                if (isset($variationData['id'])) {
                    // Update existing variation
                    $variation = $product->variations()->find($variationData['id']);
                    if ($variation) {
                        $variation->update([
                            'size' => $variationData['size'],
                            'stock_quantity' => $variationData['stock_quantity'],
                        ]);
                        $existingVariationIds[] = $variation->id;
                    }
                } else {
                    // Create new variation
                    if (!empty($variationData['size']) && isset($variationData['stock_quantity'])) {
                        $newVariation = $product->variations()->create([
                            'size' => $variationData['size'],
                            'stock_quantity' => $variationData['stock_quantity'],
                        ]);
                        $existingVariationIds[] = $newVariation->id;
                    }
                }
            }
            
            // Delete variations that were removed
            $product->variations()->whereNotIn('id', $existingVariationIds)->delete();
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $bucket = config('services.supabase.buckets.products');

        // Delete from Supabase when the stored value is a Supabase path or URL.
        $rawImage = $product->getRawOriginal('image');
        if ($rawImage) {
            $storagePath = $this->extractProductStoragePath($rawImage);
            if ($storagePath) {
                $this->storage->delete($bucket, $storagePath);
            } elseif (str_starts_with($rawImage, '/images/products/')) {
                // Legacy local files are removed only when the exact path is under this directory.
                $localPath = public_path('images/products/' . basename($rawImage));
                if (is_file($localPath)) {
                    @unlink($localPath);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Resolve the stored product image value to a path inside the product bucket.
     * Supports Supabase public URLs, products/... paths, and legacy bare filenames.
     */
    private function extractProductStoragePath(string $value): ?string
    {
        $bucket = config('services.supabase.buckets.products', 'product-images');
        $value = trim($value);

        if (preg_match('#/storage/v1/object/public/' . preg_quote($bucket, '#') . '/(.+)$#', $value, $matches)) {
            return rawurldecode($matches[1]);
        }

        if (str_starts_with($value, 'products/')) {
            return $value;
        }

        if (!str_contains($value, '://') && !str_starts_with($value, '/')) {
            return 'products/' . basename($value);
        }

        return null;
    }

}
