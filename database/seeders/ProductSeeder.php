<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amigurumiCategory = Category::where('slug', 'crochet-amigurumi')->first();
        $flowersCategory = Category::where('slug', 'crochet-flowers')->first();
        $keychainsCategory = Category::where('slug', 'crochet-keychains')->first();
        $bagsCategory = Category::where('slug', 'crochet-bags')->first();
        $accessoriesCategory = Category::where('slug', 'crochet-accessories')->first();

        // Amigurumi Products
        Product::create([
            'category_id' => $amigurumiCategory->id,
            'name' => 'Bunny Amigurumi',
            'slug' => 'bunny-amigurumi',
            'description' => 'Adorable handmade crochet bunny with long ears. Perfect as a gift or home decoration. Made with premium cotton yarn and filled with hypoallergenic stuffing. Height approximately 15cm.',
            'price' => 95000,
            'stock' => 10,
            'image' => 'bunny-amigurumi.jpg',
            'is_featured' => true,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $amigurumiCategory->id,
            'name' => 'Bear Amigurumi',
            'slug' => 'bear-amigurumi',
            'description' => 'Cute handmade crochet bear with a sweet smile. Soft and huggable, perfect for kids and collectors. Made with love using high-quality materials. Height approximately 18cm.',
            'price' => 110000,
            'stock' => 8,
            'image' => 'bear-amigurumi.jpg',
            'is_featured' => true,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $amigurumiCategory->id,
            'name' => 'Frog Amigurumi',
            'slug' => 'frog-amigurumi',
            'description' => 'Charming handmade crochet frog with big eyes. A unique gift that brings joy. Carefully crafted with attention to detail. Height approximately 12cm.',
            'price' => 85000,
            'stock' => 12,
            'image' => 'frog-amigurumi.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $amigurumiCategory->id,
            'name' => 'Cat Amigurumi',
            'slug' => 'cat-amigurumi',
            'description' => 'Sweet handmade crochet cat with striped pattern. Perfect companion for cat lovers. Made with soft yarn and love. Height approximately 16cm.',
            'price' => 100000,
            'stock' => 6,
            'image' => 'cat-amigurumi.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        // Flowers Products
        Product::create([
            'category_id' => $flowersCategory->id,
            'name' => 'Tulip Bouquet',
            'slug' => 'tulip-bouquet',
            'description' => 'Beautiful handmade crochet tulip bouquet. Never wilts, lasts forever. Perfect for home decoration or as a unique gift. Set of 5 tulips in various colors.',
            'price' => 125000,
            'stock' => 15,
            'image' => 'tulip-bouquet.jpg',
            'is_featured' => true,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $flowersCategory->id,
            'name' => 'Sunflower Crochet',
            'slug' => 'sunflower-crochet',
            'description' => 'Cheerful handmade crochet sunflower. Bright and beautiful, bringing warmth to any space. Single stem with detailed petals. Height approximately 30cm.',
            'price' => 75000,
            'stock' => 20,
            'image' => 'sunflower-crochet.jpg',
            'is_featured' => true,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $flowersCategory->id,
            'name' => 'Rose Bouquet',
            'slug' => 'rose-bouquet',
            'description' => 'Elegant handmade crochet rose bouquet. Symbol of everlasting love. Perfect for weddings, anniversaries, or special occasions. Set of 3 roses with leaves.',
            'price' => 135000,
            'stock' => 10,
            'image' => 'rose-bouquet.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        // Keychains Products
        Product::create([
            'category_id' => $keychainsCategory->id,
            'name' => 'Mini Bear Keychain',
            'slug' => 'mini-bear-keychain',
            'description' => 'Cute mini handmade crochet bear keychain. Perfect accessory for your keys or bag. Made with durable yarn and secure keyring. Size approximately 6cm.',
            'price' => 35000,
            'stock' => 30,
            'image' => 'mini-bear-keychain.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $keychainsCategory->id,
            'name' => 'Flower Keychain',
            'slug' => 'flower-keychain',
            'description' => 'Adorable handmade crochet flower keychain. Colorful and charming, adds personality to your belongings. Available in multiple colors. Size approximately 5cm.',
            'price' => 30000,
            'stock' => 35,
            'image' => 'flower-keychain.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $keychainsCategory->id,
            'name' => 'Heart Keychain',
            'slug' => 'heart-keychain',
            'description' => 'Sweet handmade crochet heart keychain. Perfect little gift for loved ones. Made with soft yarn in beautiful colors. Size approximately 5cm.',
            'price' => 28000,
            'stock' => 40,
            'image' => 'heart-keychain.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        // Bags Products
        Product::create([
            'category_id' => $bagsCategory->id,
            'name' => 'Tote Bag Classic',
            'slug' => 'tote-bag-classic',
            'description' => 'Stylish handmade crochet tote bag. Perfect for daily use, shopping, or beach trips. Sturdy construction with comfortable handles. Size: 35x40cm.',
            'price' => 185000,
            'stock' => 5,
            'image' => 'tote-bag-classic.jpg',
            'is_featured' => true,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $bagsCategory->id,
            'name' => 'Mini Bucket Bag',
            'slug' => 'mini-bucket-bag',
            'description' => 'Trendy handmade crochet mini bucket bag. Fashionable and functional with adjustable strap. Perfect for casual outings. Size: 18x20cm.',
            'price' => 145000,
            'stock' => 7,
            'image' => 'mini-bucket-bag.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $bagsCategory->id,
            'name' => 'Crossbody Bag',
            'slug' => 'crossbody-bag',
            'description' => 'Chic handmade crochet crossbody bag. Hands-free convenience with style. Adjustable strap and secure closure. Size: 20x15cm.',
            'price' => 165000,
            'stock' => 6,
            'image' => 'crossbody-bag.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        // Accessories Products
        Product::create([
            'category_id' => $accessoriesCategory->id,
            'name' => 'Hair Scrunchie Set',
            'slug' => 'hair-scrunchie-set',
            'description' => 'Pretty handmade crochet hair scrunchies. Gentle on hair, stylish and functional. Set of 3 scrunchies in complementary colors. Perfect for any hairstyle.',
            'price' => 45000,
            'stock' => 25,
            'image' => 'hair-scrunchie-set.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $accessoriesCategory->id,
            'name' => 'Coaster Set',
            'slug' => 'coaster-set',
            'description' => 'Beautiful handmade crochet coaster set. Protects your surfaces in style. Set of 4 coasters in matching colors. Diameter approximately 10cm each.',
            'price' => 55000,
            'stock' => 18,
            'image' => 'coaster-set.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $accessoriesCategory->id,
            'name' => 'Phone Pouch',
            'slug' => 'phone-pouch',
            'description' => 'Practical handmade crochet phone pouch. Protects your phone with style. Soft interior lining and secure closure. Fits most smartphone sizes.',
            'price' => 65000,
            'stock' => 12,
            'image' => 'phone-pouch.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $accessoriesCategory->id,
            'name' => 'Headband Set',
            'slug' => 'headband-set',
            'description' => 'Cute handmade crochet headband set. Comfortable and fashionable. Set of 2 headbands with flower details. One size fits all.',
            'price' => 50000,
            'stock' => 20,
            'image' => 'headband-set.jpg',
            'is_featured' => false,
            'is_available' => true,
        ]);
    }
}
