<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class MigrateProductImagesToSupabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'knotted:migrate-product-images 
                            {--dry-run : Run without making changes}
                            {--force : Skip confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate product images from local storage to Supabase Storage';

    /**
     * Product ID to local filename mapping
     */
    protected $imageMapping = [
        1 => '1793975875_6aede64328ceb.jpeg',  // Bunny Amigurumi
        2 => '1793975638_6aede55660657.jpeg',  // Bear Plushie
        3 => '1793977034_6aedeaca39bcb.jpeg',  // Crochet Tote Bag
        4 => '1793976863_6aedea1ff2624.jpeg',  // Mini Pouch
        5 => '1793976655_6aede94f203e0.jpeg',  // Crochet Beanie
        6 => '1793976538_6aede8da299f3.jpeg',  // Infinity Scarf
        7 => '1793976316_6aede7fc2128c.jpeg',  // Crochet Coaster Set
        8 => '1793975518_6aede4deaab52.jpeg',  // Wall Hanging Decor
    ];

    protected $stats = [
        'total' => 0,
        'success' => 0,
        'failed' => 0,
        'skipped' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('===========================================');
        $this->info('  KNOTTED Product Image Migration');
        $this->info('  Local → Supabase Storage');
        $this->info('===========================================');
        $this->newLine();

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        // Step 1: Pre-flight checks
        $this->info('Step 1: Running pre-flight checks...');
        if (!$this->preflightChecks()) {
            return 1;
        }
        $this->info('✓ All pre-flight checks passed');
        $this->newLine();

        // Step 2: Display migration plan
        $this->displayMigrationPlan();

        // Step 3: Confirm unless forced
        if (!$dryRun && !$force) {
            if (!$this->confirm('Do you want to proceed with the migration?')) {
                $this->warn('Migration cancelled by user');
                return 0;
            }
            $this->newLine();
        }

        // Step 4: Execute migration
        $this->info('Step 2: Starting migration...');
        $this->newLine();

        $this->stats['total'] = count($this->imageMapping);
        
        foreach ($this->imageMapping as $productId => $filename) {
            $this->migrateProductImage($productId, $filename, $dryRun);
        }

        // Step 5: Display results
        $this->displayResults($dryRun);

        // Step 6: Verification (only if not dry-run)
        if (!$dryRun && $this->stats['success'] > 0) {
            $this->newLine();
            $this->info('Step 3: Verifying migration...');
            $this->verifyMigration();
        }

        return 0;
    }

    /**
     * Pre-flight checks
     */
    protected function preflightChecks(): bool
    {
        $passed = true;

        // Check 1: Verify Supabase disk configuration
        $this->line('  → Checking Supabase disk configuration...');
        try {
            $disk = Storage::disk('supabase_products');
            $this->info('    ✓ Supabase disk configured');
        } catch (\Exception $e) {
            $this->error('    ✗ Supabase disk not configured: ' . $e->getMessage());
            $passed = false;
        }

        // Check 2: Verify local image directory exists
        $this->line('  → Checking local image directory...');
        $localPath = public_path('images/products');
        if (!is_dir($localPath)) {
            $this->error('    ✗ Local directory not found: ' . $localPath);
            $passed = false;
        } else {
            $this->info('    ✓ Local directory exists');
        }

        // Check 3: Verify all mapped files exist
        $this->line('  → Checking mapped image files...');
        $missingFiles = [];
        foreach ($this->imageMapping as $productId => $filename) {
            $filePath = $localPath . '/' . $filename;
            if (!file_exists($filePath)) {
                $missingFiles[] = $filename . " (Product ID: $productId)";
            }
        }
        if (count($missingFiles) > 0) {
            $this->error('    ✗ Missing files:');
            foreach ($missingFiles as $file) {
                $this->line('      - ' . $file);
            }
            $passed = false;
        } else {
            $this->info('    ✓ All 8 mapped files exist');
        }

        // Check 4: Verify all products exist in database
        $this->line('  → Checking products in database...');
        $missingProducts = [];
        foreach (array_keys($this->imageMapping) as $productId) {
            $product = Product::find($productId);
            if (!$product) {
                $missingProducts[] = $productId;
            }
        }
        if (count($missingProducts) > 0) {
            $this->error('    ✗ Missing products: ' . implode(', ', $missingProducts));
            $passed = false;
        } else {
            $this->info('    ✓ All 8 products exist in database');
        }

        // Check 5: Test Supabase connectivity
        $this->line('  → Testing Supabase Storage connectivity...');
        try {
            $disk = Storage::disk('supabase_products');
            // Try to list files (this tests auth and bucket access)
            $disk->files();
            $this->info('    ✓ Supabase Storage accessible');
        } catch (\Exception $e) {
            $this->error('    ✗ Cannot connect to Supabase Storage: ' . $e->getMessage());
            $passed = false;
        }

        return $passed;
    }

    /**
     * Display migration plan
     */
    protected function displayMigrationPlan()
    {
        $this->table(
            ['Product ID', 'Product Name', 'Local File', 'File Size'],
            collect($this->imageMapping)->map(function ($filename, $productId) {
                $product = Product::find($productId);
                $filePath = public_path('images/products/' . $filename);
                $fileSize = file_exists($filePath) ? $this->formatBytes(filesize($filePath)) : 'N/A';
                
                return [
                    $productId,
                    $product ? $product->name : 'NOT FOUND',
                    $filename,
                    $fileSize,
                ];
            })->values()->toArray()
        );
    }

    /**
     * Migrate single product image
     */
    protected function migrateProductImage(int $productId, string $filename, bool $dryRun)
    {
        $this->line("Processing Product ID {$productId}...");

        try {
            // Step 1: Get product
            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception("Product not found");
            }

            // Step 2: Check local file
            $localPath = public_path('images/products/' . $filename);
            if (!file_exists($localPath)) {
                throw new \Exception("Local file not found: {$filename}");
            }

            $fileSize = filesize($localPath);
            $mimeType = mime_content_type($localPath);

            // Step 3: Generate Supabase path
            $supabasePath = 'products/' . $filename;

            // Step 4: Check if file already exists in Supabase
            $disk = Storage::disk('supabase_products');
            $alreadyExists = false;
            
            try {
                $alreadyExists = $disk->exists($supabasePath);
            } catch (\Exception $e) {
                // Exists check might fail, continue anyway
            }

            if ($alreadyExists) {
                $this->warn("  ⚠ File already exists in Supabase, checking database...");
                
                // Check if database already has correct reference
                $currentImage = $product->image;
                $supabaseUrl = $disk->url($supabasePath);
                
                if ($currentImage === $supabaseUrl) {
                    $this->info("  ✓ Already migrated and database is correct");
                    $this->stats['skipped']++;
                    return;
                }
            }

            if ($dryRun) {
                $this->info("  [DRY RUN] Would upload: {$filename} ({$this->formatBytes($fileSize)}, {$mimeType})");
                $this->info("  [DRY RUN] Would update database with Supabase URL");
                $this->stats['success']++;
                return;
            }

            // Step 5: Upload to Supabase
            $this->line("  → Uploading to Supabase...");
            $fileContents = file_get_contents($localPath);
            
            $uploaded = $disk->put($supabasePath, $fileContents, [
                'ContentType' => $mimeType,
                'CacheControl' => 'public, max-age=31536000',
            ]);

            if (!$uploaded) {
                throw new \Exception("Upload failed");
            }

            // Step 6: Verify upload
            $this->line("  → Verifying upload...");
            if (!$disk->exists($supabasePath)) {
                throw new \Exception("File not found in Supabase after upload");
            }

            // Step 7: Get public URL
            $supabaseUrl = $disk->url($supabasePath);
            $this->line("  → Supabase URL: {$supabaseUrl}");

            // Step 8: Update database
            $this->line("  → Updating database...");
            DB::beginTransaction();
            try {
                $product->update([
                    'image' => $supabaseUrl,
                ]);
                DB::commit();
                $this->info("  ✓ Successfully migrated {$product->name}");
                $this->stats['success']++;
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception("Database update failed: " . $e->getMessage());
            }

        } catch (\Exception $e) {
            $this->error("  ✗ Failed: " . $e->getMessage());
            $this->stats['failed']++;
        }

        $this->newLine();
    }

    /**
     * Display migration results
     */
    protected function displayResults(bool $dryRun)
    {
        $this->newLine();
        $this->info('===========================================');
        $this->info('  Migration ' . ($dryRun ? 'Plan' : 'Results'));
        $this->info('===========================================');
        
        $this->table(
            ['Status', 'Count'],
            [
                ['Total Products', $this->stats['total']],
                ['Successful', $this->stats['success']],
                ['Failed', $this->stats['failed']],
                ['Skipped', $this->stats['skipped']],
            ]
        );

        if ($this->stats['failed'] > 0) {
            $this->error('⚠ Some products failed to migrate. Please check the errors above.');
        } elseif ($this->stats['success'] > 0 && !$dryRun) {
            $this->info('✓ Migration completed successfully!');
        }
    }

    /**
     * Verify migration
     */
    protected function verifyMigration()
    {
        $disk = Storage::disk('supabase_products');
        $allGood = true;

        foreach ($this->imageMapping as $productId => $filename) {
            $product = Product::find($productId);
            if (!$product) continue;

            $supabasePath = 'products/' . $filename;
            
            // Check 1: File exists in Supabase
            try {
                $exists = $disk->exists($supabasePath);
                if (!$exists) {
                    $this->warn("  ⚠ File not found in Supabase: {$filename}");
                    $allGood = false;
                    continue;
                }
            } catch (\Exception $e) {
                $this->warn("  ⚠ Cannot verify file in Supabase: {$filename}");
                $allGood = false;
                continue;
            }

            // Check 2: Database has correct URL
            $expectedUrl = $disk->url($supabasePath);
            if ($product->image !== $expectedUrl) {
                $this->warn("  ⚠ Product {$productId} database URL mismatch");
                $this->line("    Expected: {$expectedUrl}");
                $this->line("    Actual: {$product->image}");
                $allGood = false;
            }
        }

        if ($allGood) {
            $this->info('  ✓ All files verified in Supabase Storage');
            $this->info('  ✓ All database references are correct');
        } else {
            $this->warn('  ⚠ Some verification checks failed');
        }
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
