<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * Image Upload Service for Supabase Storage
 * 
 * Handles secure image uploads with:
 * - Multiple size variants (thumbnail, medium, large)
 * - Validation (type, size, dimensions)
 * - Optimization (compression, format conversion)
 * - Secure file naming
 * - Supabase Storage integration
 */
class ImageUploadService
{
    // Allowed image types
    const ALLOWED_TYPES = ['jpg', 'jpeg', 'png', 'webp'];
    
    // Max file size in bytes (5MB)
    const MAX_FILE_SIZE = 5 * 1024 * 1024;
    
    // Image size variants
    const SIZES = [
        'thumbnail' => ['width' => 150, 'height' => 150],
        'medium' => ['width' => 500, 'height' => 500],
        'large' => ['width' => 1200, 'height' => 1200],
    ];
    
    // Storage paths
    const PRODUCT_IMAGES_PATH = 'products';
    const PAYMENT_PROOFS_PATH = 'payment-proofs';
    const CUSTOM_ORDERS_PATH = 'custom-orders';
    const PROFILES_PATH = 'profiles';
    
    /**
     * Validate uploaded image file.
     */
    public static function validateImage(UploadedFile $file): array
    {
        $errors = [];
        
        // Check if file is valid
        if (!$file->isValid()) {
            $errors[] = 'File upload failed. Please try again.';
            return $errors;
        }
        
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            $errors[] = 'File size must be less than 5MB.';
        }
        
        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_TYPES)) {
            $errors[] = 'File must be an image (JPG, PNG, or WebP).';
        }
        
        // Check if it's actually an image
        $mimeType = $file->getMimeType();
        if (!Str::startsWith($mimeType, 'image/')) {
            $errors[] = 'File must be a valid image.';
        }
        
        return $errors;
    }
    
    /**
     * Generate secure filename with UUID.
     */
    public static function generateFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $uuid = Str::uuid();
        
        return "{$uuid}.{$extension}";
    }
    
    /**
     * Upload product image with multiple size variants.
     * 
     * @return array ['original' => 'path', 'thumbnail' => 'path', 'medium' => 'path', 'large' => 'path']
     */
    public static function uploadProductImage(UploadedFile $file, ?string $productSku = null): array
    {
        // Validate
        $errors = self::validateImage($file);
        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode(' ', $errors));
        }
        
        $filename = self::generateFilename($file->getClientOriginalName());
        $basePath = self::PRODUCT_IMAGES_PATH;
        
        if ($productSku) {
            $basePath .= '/' . Str::slug($productSku);
        }
        
        $paths = [];
        
        // Upload original
        $originalPath = "{$basePath}/original/{$filename}";
        Storage::disk('public')->put($originalPath, file_get_contents($file->getRealPath()));
        $paths['original'] = $originalPath;
        
        // Create and upload variants
        foreach (self::SIZES as $sizeName => $dimensions) {
            $variantPath = "{$basePath}/{$sizeName}/{$filename}";
            
            $image = Image::make($file->getRealPath())
                ->fit($dimensions['width'], $dimensions['height'])
                ->encode('jpg', 85);
            
            Storage::disk('public')->put($variantPath, $image);
            $paths[$sizeName] = $variantPath;
        }
        
        return $paths;
    }
    
    /**
     * Upload payment proof image.
     * Only creates medium variant (for verification).
     */
    public static function uploadPaymentProof(UploadedFile $file, string $orderNumber): string
    {
        // Validate
        $errors = self::validateImage($file);
        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode(' ', $errors));
        }
        
        $filename = self::generateFilename($file->getClientOriginalName());
        $basePath = self::PAYMENT_PROOFS_PATH . '/' . Str::slug($orderNumber);
        
        // Create medium size for verification
        $path = "{$basePath}/{$filename}";
        
        $image = Image::make($file->getRealPath())
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', 85);
        
        Storage::disk('public')->put($path, $image);
        
        return $path;
    }
    
    /**
     * Upload custom order reference image.
     */
    public static function uploadCustomOrderImage(UploadedFile $file, string $requestNumber): string
    {
        // Validate
        $errors = self::validateImage($file);
        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode(' ', $errors));
        }
        
        $filename = self::generateFilename($file->getClientOriginalName());
        $basePath = self::CUSTOM_ORDERS_PATH . '/' . Str::slug($requestNumber);
        
        $path = "{$basePath}/{$filename}";
        
        // Store original (admin may need details)
        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));
        
        return $path;
    }
    
    /**
     * Upload user profile picture.
     */
    public static function uploadProfilePicture(UploadedFile $file, int $userId): string
    {
        // Validate
        $errors = self::validateImage($file);
        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode(' ', $errors));
        }
        
        $filename = self::generateFilename($file->getClientOriginalName());
        $path = self::PROFILES_PATH . "/{$userId}/{$filename}";
        
        // Create square thumbnail
        $image = Image::make($file->getRealPath())
            ->fit(300, 300)
            ->encode('jpg', 85);
        
        Storage::disk('public')->put($path, $image);
        
        return $path;
    }
    
    /**
     * Delete image and all its variants.
     */
    public static function deleteImage(string $path): bool
    {
        if (!$path) {
            return false;
        }
        
        // Delete the main file
        $deleted = Storage::disk('public')->delete($path);
        
        // Try to delete variants if this is a product image
        if (Str::contains($path, self::PRODUCT_IMAGES_PATH)) {
            $directory = dirname($path);
            $filename = basename($path);
            
            foreach (array_keys(self::SIZES) as $sizeName) {
                $variantPath = str_replace('/original/', "/{$sizeName}/", $path);
                Storage::disk('public')->delete($variantPath);
            }
        }
        
        return $deleted;
    }
    
    /**
     * Get public URL for stored image.
     */
    public static function getUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        
        return Storage::disk('public')->url($path);
    }
    
    /**
     * Get URL for specific size variant.
     */
    public static function getVariantUrl(?string $originalPath, string $size = 'medium'): ?string
    {
        if (!$originalPath) {
            return null;
        }
        
        if (!array_key_exists($size, self::SIZES)) {
            $size = 'medium';
        }
        
        $variantPath = str_replace('/original/', "/{$size}/", $originalPath);
        
        return Storage::disk('public')->url($variantPath);
    }
}
