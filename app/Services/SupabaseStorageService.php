<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class SupabaseStorageService
{
    protected string $projectUrl;
    protected string $apiKey;
    protected string $storageUrl;

    public function __construct()
    {
        $this->projectUrl = config('services.supabase.url');
        $this->apiKey = config('services.supabase.anon_key');
        $this->storageUrl = config('services.supabase.storage_url');
    }

    /**
     * Upload file to Supabase Storage bucket
     *
     * @param UploadedFile $file
     * @param string $bucket
     * @param string|null $path
     * @return array ['success' => bool, 'path' => string|null, 'url' => string|null, 'error' => string|null]
     */
    public function upload(UploadedFile $file, string $bucket, ?string $path = null): array
    {
        try {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $path ? $path . '/' . $filename : $filename;

            // Get file contents
            $fileContents = file_get_contents($file->getRealPath());

            // Upload to Supabase Storage
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => $file->getMimeType(),
            ])->withBody($fileContents, $file->getMimeType())
              ->post("{$this->storageUrl}/object/{$bucket}/{$filePath}");

            if ($response->successful()) {
                $publicUrl = $this->getPublicUrl($bucket, $filePath);
                
                return [
                    'success' => true,
                    'path' => $filePath,
                    'url' => $publicUrl,
                    'error' => null,
                ];
            }

            Log::error('Supabase Storage upload failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'path' => null,
                'url' => null,
                'error' => $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Supabase Storage upload exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'path' => null,
                'url' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete file from Supabase Storage bucket
     *
     * @param string $bucket
     * @param string $filePath
     * @return bool
     */
    public function delete(string $bucket, string $filePath): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->delete("{$this->storageUrl}/object/{$bucket}/{$filePath}");

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Supabase Storage delete exception', [
                'message' => $e->getMessage(),
                'file' => $filePath,
            ]);

            return false;
        }
    }

    /**
     * Get public URL for a file in Supabase Storage
     *
     * @param string $bucket
     * @param string $filePath
     * @return string
     */
    public function getPublicUrl(string $bucket, string $filePath): string
    {
        return "{$this->storageUrl}/object/public/{$bucket}/{$filePath}";
    }

    /**
     * Check if file exists in bucket
     *
     * @param string $bucket
     * @param string $filePath
     * @return bool
     */
    public function exists(string $bucket, string $filePath): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->head("{$this->storageUrl}/object/{$bucket}/{$filePath}");

            return $response->successful();

        } catch (\Exception $e) {
            return false;
        }
    }
}
