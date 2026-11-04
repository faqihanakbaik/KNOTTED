<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Crochet Amigurumi',
                'slug' => 'crochet-amigurumi',
                'description' => 'Adorable handmade crochet stuffed animals and characters.',
            ],
            [
                'name' => 'Crochet Flowers',
                'slug' => 'crochet-flowers',
                'description' => 'Beautiful handcrafted crochet flowers that last forever.',
            ],
            [
                'name' => 'Crochet Keychains',
                'slug' => 'crochet-keychains',
                'description' => 'Cute and unique crochet keychains for everyday use.',
            ],
            [
                'name' => 'Crochet Bags',
                'slug' => 'crochet-bags',
                'description' => 'Stylish and functional handmade crochet bags.',
            ],
            [
                'name' => 'Crochet Accessories',
                'slug' => 'crochet-accessories',
                'description' => 'Various handmade crochet accessories to complete your style.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
