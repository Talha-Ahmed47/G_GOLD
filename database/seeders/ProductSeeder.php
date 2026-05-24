<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'American Gold Eagle', 'metal_type' => 'gold', 'weight_oz' => 1, 'premium_percentage' => 4.5, 'image_url' => 'website/images/tm-gold-01.jpg'],
            ['name' => 'Canadian Maple Leaf', 'metal_type' => 'gold', 'weight_oz' => 1, 'premium_percentage' => 3.8, 'image_url' => 'website/images/tm-gold-02.jpg'],
            ['name' => 'South African Krugerrand', 'metal_type' => 'gold', 'weight_oz' => 1, 'premium_percentage' => 4.0, 'image_url' => 'website/images/tm-gold-03.jpg'],
            ['name' => 'Austrian Philharmonic', 'metal_type' => 'gold', 'weight_oz' => 1, 'premium_percentage' => 3.5, 'image_url' => 'website/images/tm-gold-04.jpg'],
            
            ['name' => 'PAMP Suisse Bar', 'metal_type' => 'gold', 'weight_oz' => 1, 'premium_percentage' => 2.5, 'image_url' => 'website/images/tm-gold-04.jpg'],
            ['name' => 'Credit Suisse Bar', 'metal_type' => 'gold', 'weight_oz' => 1, 'premium_percentage' => 2.2, 'image_url' => 'website/images/tm-gold-03.jpg'],
            ['name' => 'Perth Mint Bar', 'metal_type' => 'gold', 'weight_oz' => 10, 'premium_percentage' => 1.8, 'image_url' => 'website/images/tm-gold-02.jpg'],
            ['name' => 'Valcambi CombiBar', 'metal_type' => 'gold', 'weight_oz' => 1.6075, 'premium_percentage' => 5.0, 'image_url' => 'website/images/tm-gold-01.jpg'], // 50g = ~1.6075oz

            ['name' => 'American Silver Eagle', 'metal_type' => 'silver', 'weight_oz' => 1, 'premium_percentage' => 15.0, 'image_url' => 'website/images/tm-silver-01.jpg'],
            ['name' => 'Canadian Silver Maple', 'metal_type' => 'silver', 'weight_oz' => 1, 'premium_percentage' => 12.0, 'image_url' => 'website/images/tm-silver-02.jpg'],
            ['name' => 'PAMP Silver Bar', 'metal_type' => 'silver', 'weight_oz' => 10, 'premium_percentage' => 8.0, 'image_url' => 'website/images/tm-silver-03.jpg'],
            ['name' => 'British Britannia', 'metal_type' => 'silver', 'weight_oz' => 1, 'premium_percentage' => 11.0, 'image_url' => 'website/images/tm-silver-04.jpg'],

            ['name' => 'American Platinum Eagle', 'metal_type' => 'platinum', 'weight_oz' => 1, 'premium_percentage' => 6.0, 'image_url' => 'website/images/tm-silver-02.jpg'],
            ['name' => 'Canadian Platinum Maple', 'metal_type' => 'platinum', 'weight_oz' => 1, 'premium_percentage' => 5.5, 'image_url' => 'website/images/tm-silver-03.jpg'],
            ['name' => 'PAMP Platinum Bar', 'metal_type' => 'platinum', 'weight_oz' => 1, 'premium_percentage' => 4.5, 'image_url' => 'website/images/tm-silver-04.jpg'],
            ['name' => 'Australian Platinum Koala', 'metal_type' => 'platinum', 'weight_oz' => 1, 'premium_percentage' => 7.0, 'image_url' => 'website/images/tm-silver-01.jpg'],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
