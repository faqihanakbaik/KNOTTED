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
        $this->projectUrl = rtrim((string) config('services.supabase.url'), '/');
        // Server-side uploads/deletes should use the secret service key.
        // Keep the anon key only as a local-development fallback when explicitly configured.
        $this->apiKey = (string) (config('services.supabase.service_key') ?: config('services.supabase.anon_key'));
        $this->storageUrl = rtrim((string) config('services.supabase.storage_url'), '/');
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

            if ($this->storageUrl === '' || $this->apiKey === '') {
                throw new \RuntimeException('Supabase Storage is not configured. Set SUPABASE_URL and SUPABASE_SERVICE_KEY.');
            }

            // Encode each path segment while preserving folder separators.
            $encodedBucket = rawurlencode($bucket);
            $encodedPath = implode('/', array_map('rawurlencode', explode('/', $filePath)));
            $fileContents = file_get_contents($file->getRealPath());
            if ($fileContents === false) {
                throw new \RuntimeException('Unable to read uploaded image.');
            }

            // Upload to Supabase Storage using the server-side service key.
            $mimeType = $file->getMimeType() ?: 'application/octet-stream';
            $response = Http::timeout(30)->withHeaders([
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => $mimeType,
                'x-upsert' => 'false',
            ])->withBody($fileContents, $mimeType)
              ->post("{$this->storageUrl}/object/{$encodedBucket}/{$encodedPath}");

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
            if ($this->storageUrl === '' || $this->apiKey === '') {
                return false;
            }

            $encodedBucket = rawurlencode($bucket);
            $encodedPath = implode('/', array_map('rawurlencode', explode('/', ltrim($filePath, '/'))));
            $response = Http::timeout(30)->withHeaders([
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->delete("{$this->storageUrl}/object/{$encodedBucket}/{$encodedPath}");

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
        $encodedBucket = rawurlencode($bucket);
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', ltrim($filePath, '/'))));
        return "{$this->storageUrl}/object/public/{$encodedBucket}/{$encodedPath}";
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
            if ($this->storageUrl === '' || $this->apiKey === '') {
                return false;
            }

            $encodedBucket = rawurlencode($bucket);
            $encodedPath = implode('/', array_map('rawurlencode', explode('/', ltrim($filePath, '/'))));
            $response = Http::timeout(15)->withHeaders([
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->head("{$this->storageUrl}/object/{$encodedBucket}/{$encodedPath}");

            return $response->successful();

        } catch (\Exception $e) {
            return false;
        }
    }
}
