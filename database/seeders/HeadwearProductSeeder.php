<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Str;

class HeadwearProductSeeder extends Seeder
{
    /**
     * Seed headwear products per PRD requirements.
     * KNOTTED specializes in handmade crochet/knitted HEADWEAR ONLY.
     */
    public function run(): void
    {
        // Create headwear categories
        $categories = [
            'Bucket Hats' => 'Stylish crochet bucket hats perfect for any season',
            'Beanies' => 'Cozy knitted beanies to keep you warm',
            'Berets' => 'Classic French-style crochet berets',
            'Balaclavas' => 'Full-coverage knitted balaclavas',
            'Bonnets' => 'Cute vintage-style bonnets',
            'Headbands' => 'Fashionable crochet headbands',
        ];

        foreach ($categories as $name => $description) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                ]
            );
        }

        // Headwear products data
        $products = [
            // BUCKET HATS
            [
                'name' => 'Classic Crochet Bucket Hat',
                'category' => 'Bucket Hats',
                'description' => 'Handmade crochet bucket hat with a timeless design. Perfect for sunny days and adds a bohemian touch to any outfit. Made with soft, breathable cotton yarn.',
                'price' => 145000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Cream', 'hex' => '#F5F5DC', 'sizes' => ['S', 'M', 'L'], 'stock' => 15],
                    ['color' => 'Dusty Pink', 'hex' => '#E7B9C3', 'sizes' => ['S', 'M', 'L'], 'stock' => 12],
                    ['color' => 'Sage Green', 'hex' => '#9CAF88', 'sizes' => ['S', 'M', 'L'], 'stock' => 10],
                ],
            ],
            [
                'name' => 'Summer Wave Bucket Hat',
                'category' => 'Bucket Hats',
                'description' => 'Lightweight crochet bucket hat featuring delicate wave patterns. Breathable design ideal for beach days and summer festivals. Handcrafted with love.',
                'price' => 165000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Off White', 'hex' => '#FAFAFA', 'sizes' => ['M', 'L'], 'stock' => 8],
                    ['color' => 'Sky Blue', 'hex' => '#87CEEB', 'sizes' => ['M', 'L'], 'stock' => 6],
                    ['color' => 'Lavender', 'hex' => '#E6E6FA', 'sizes' => ['M', 'L'], 'stock' => 5],
                ],
            ],

            // BEANIES
            [
                'name' => 'Cozy Ribbed Beanie',
                'category' => 'Beanies',
                'description' => 'Warm and snug ribbed knit beanie perfect for cold weather. Features a folded brim and stretchy fit. Made with premium wool blend for maximum comfort.',
                'price' => 125000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Charcoal', 'hex' => '#36454F', 'sizes' => ['One Size'], 'stock' => 20],
                    ['color' => 'Camel', 'hex' => '#C19A6B', 'sizes' => ['One Size'], 'stock' => 18],
                    ['color' => 'Burgundy', 'hex' => '#800020', 'sizes' => ['One Size'], 'stock' => 15],
                ],
            ],
            [
                'name' => 'Pom-Pom Beanie',
                'category' => 'Beanies',
                'description' => 'Adorable knitted beanie with a fluffy pom-pom on top. Soft, warm, and stylish. Perfect for adding a playful touch to your winter wardrobe.',
                'price' => 135000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Blush Pink', 'hex' => '#F8E8EB', 'sizes' => ['One Size'], 'stock' => 12],
                    ['color' => 'Navy', 'hex' => '#000080', 'sizes' => ['One Size'], 'stock' => 14],
                    ['color' => 'Forest Green', 'hex' => '#228B22', 'sizes' => ['One Size'], 'stock' => 10],
                ],
            ],

            // BERETS
            [
                'name' => 'Classic French Beret',
                'category' => 'Berets',
                'description' => 'Timeless crochet beret inspired by Parisian style. Lightweight and chic, perfect for adding elegance to any outfit. Handmade with fine yarn.',
                'price' => 155000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Black', 'hex' => '#000000', 'sizes' => ['One Size'], 'stock' => 10],
                    ['color' => 'Dusty Rose', 'hex' => '#C98F9D', 'sizes' => ['One Size'], 'stock' => 8],
                    ['color' => 'Cream', 'hex' => '#FFFDD0', 'sizes' => ['One Size'], 'stock' => 7],
                ],
            ],
            [
                'name' => 'Textured Beret',
                'category' => 'Berets',
                'description' => 'Unique textured crochet beret with intricate stitch patterns. A statement piece that combines comfort with artistic flair. Limited edition design.',
                'price' => 175000,
                'product_type' => 'pre_order',
                'lead_time_days' => 7,
                'variations' => [
                    ['color' => 'Terracotta', 'hex' => '#E27C5D', 'sizes' => ['One Size'], 'stock' => 3],
                    ['color' => 'Mustard', 'hex' => '#FFDB58', 'sizes' => ['One Size'], 'stock' => 2],
                ],
            ],

            // BALACLAVAS
            [
                'name' => 'Winter Balaclava',
                'category' => 'Balaclavas',
                'description' => 'Full-coverage knitted balaclava for ultimate warmth. Soft, breathable, and comfortable. Ideal for winter sports or cold commutes.',
                'price' => 185000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Black', 'hex' => '#000000', 'sizes' => ['One Size'], 'stock' => 8],
                    ['color' => 'Gray', 'hex' => '#808080', 'sizes' => ['One Size'], 'stock' => 6],
                    ['color' => 'Cream', 'hex' => '#FFFDD0', 'sizes' => ['One Size'], 'stock' => 5],
                ],
            ],

            // BONNETS
            [
                'name' => 'Vintage Crochet Bonnet',
                'category' => 'Bonnets',
                'description' => 'Charming vintage-style crochet bonnet with ribbon ties. Soft and delicate, perfect for photoshoots or nostalgic fashion lovers. Available for all ages.',
                'price' => 140000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Baby Pink', 'hex' => '#FFB6D9', 'sizes' => ['Baby', 'Toddler', 'Adult'], 'stock' => 10],
                    ['color' => 'Ivory', 'hex' => '#FFFFF0', 'sizes' => ['Baby', 'Toddler', 'Adult'], 'stock' => 8],
                    ['color' => 'Lilac', 'hex' => '#C8A2C8', 'sizes' => ['Baby', 'Toddler', 'Adult'], 'stock' => 6],
                ],
            ],

            // HEADBANDS
            [
                'name' => 'Flower Crochet Headband',
                'category' => 'Headbands',
                'description' => 'Beautiful crochet headband adorned with handmade flower accents. Comfortable ear-warmer style that adds a feminine touch to any look.',
                'price' => 95000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Blush Pink', 'hex' => '#F8E8EB', 'sizes' => ['One Size'], 'stock' => 15],
                    ['color' => 'Cream', 'hex' => '#FFFDD0', 'sizes' => ['One Size'], 'stock' => 12],
                    ['color' => 'Mint', 'hex' => '#98FF98', 'sizes' => ['One Size'], 'stock' => 10],
                ],
            ],
            [
                'name' => 'Twisted Knit Headband',
                'category' => 'Headbands',
                'description' => 'Modern twisted knit headband design. Warm, comfortable, and stylish. Perfect for keeping your ears warm while looking chic.',
                'price' => 85000,
                'product_type' => 'ready_stock',
                'lead_time_days' => 0,
                'variations' => [
                    ['color' => 'Charcoal', 'hex' => '#36454F', 'sizes' => ['One Size'], 'stock' => 20],
                    ['color' => 'Caramel', 'hex' => '#C68E17', 'sizes' => ['One Size'], 'stock' => 18],
                    ['color' => 'Burgundy', 'hex' => '#800020', 'sizes' => ['One Size'], 'stock' => 15],
                ],
            ],
        ];

        // Create products with variations
        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            
            if (!$category) continue;

            $slug = Str::slug($productData['name']);
            $sku = 'KNT-' . strtoupper(substr($category->slug, 0, 3)) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'category_id' => $category->id,
                    'sku' => $sku,
                    'product_type' => $productData['product_type'],
                    'lead_time_days' => $productData['lead_time_days'],
                    'stock' => 0, // Will be updated from variations
                    'is_active' => true,
                ]
            );

            // Create variations
            foreach ($productData['variations'] as $varData) {
                $sizes = is_array($varData['sizes']) ? $varData['sizes'] : [$varData['sizes']];
                
                foreach ($sizes as $size) {
                    ProductVariation::firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'color_name' => $varData['color'],
                            'size_label' => $size,
                        ],
                        [
                            'color_hex' => $varData['hex'],
                            'stock_quantity' => $varData['stock'],
                            'price_adjustment' => 0,
                        ]
                    );
                }
            }

            // Update product total stock
            $totalStock = ProductVariation::where('product_id', $product->id)->sum('stock_quantity');
            $product->update(['stock' => $totalStock]);
        }

        $this->command->info('✅ Headwear products seeded successfully!');
        $this->command->info('   - 10 products created');
        $this->command->info('   - 6 categories created');
        $this->command->info('   - Multiple color and size variations');
        $this->command->info('   - All products are HEADWEAR ONLY per PRD');
    }
}
