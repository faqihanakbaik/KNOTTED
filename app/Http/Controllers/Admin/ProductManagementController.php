<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Services\SupabaseStorageService;

class ProductManagementController extends Controller
{
    protected SupabaseStorageService $storage;

    public function __construct(SupabaseStorageService $storage)
    {
        $this->storage = $storage;
    }
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);
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
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // REQUIRED for create
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Try Supabase first, fallback to local if fails
        $bucket = config('services.supabase.buckets.products');
        $uploadResult = $this->storage->upload($request->file('image'), $bucket, 'products');

        if ($uploadResult['success']) {
            // Supabase success - store URL
            $validated['image'] = $uploadResult['url'];
        } else {
            // Fallback to local storage
            \Log::warning('Supabase upload failed, using local storage', ['error' => $uploadResult['error']]);
            
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            
            // Store as asset URL format
            $validated['image'] = asset('images/products/' . $filename);
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
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
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            $bucket = config('services.supabase.buckets.products');
            
            // Try upload to Supabase
            $uploadResult = $this->storage->upload($request->file('image'), $bucket, 'products');

            if ($uploadResult['success']) {
                // Supabase success
                // Delete old image if it was also on Supabase
                if ($product->image && str_contains($product->image, 'supabase.co')) {
                    $this->deleteImageFromUrl($product->image, $bucket);
                } elseif ($product->image) {
                    // Delete old local image
                    $oldPath = public_path('images/products/' . basename($product->image));
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                $validated['image'] = $uploadResult['url'];
            } else {
                // Fallback to local storage
                \Log::warning('Supabase upload failed, using local storage', ['error' => $uploadResult['error']]);
                
                // Delete old local image
                if ($product->image && !str_contains($product->image, 'supabase.co')) {
                    $oldPath = public_path('images/products/' . basename($product->image));
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $filename);
                
                $validated['image'] = asset('images/products/' . $filename);
            }
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $bucket = config('services.supabase.buckets.products');

        // Delete image
        if ($product->image) {
            if (str_contains($product->image, 'supabase.co')) {
                // Delete from Supabase
                $this->deleteImageFromUrl($product->image, $bucket);
            } else {
                // Delete local file
                $localPath = public_path('images/products/' . basename($product->image));
                if (file_exists($localPath)) {
                    unlink($localPath);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Helper method to extract path from Supabase URL and delete
     */
    private function deleteImageFromUrl(string $url, string $bucket): void
    {
        // Extract filename from URL
        // URL format: https://PROJECT.supabase.co/storage/v1/object/public/BUCKET/PATH
        $pattern = '/\/object\/public\/' . preg_quote($bucket, '/') . '\/(.+)$/';
        
        if (preg_match($pattern, $url, $matches)) {
            $filePath = $matches[1];
            $this->storage->delete($bucket, $filePath);
        }
    }
}
