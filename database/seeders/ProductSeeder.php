<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $elektronik = Category::where('slug', 'elektronik')->first();
        $fashionPria = Category::where('slug', 'fashion-pria')->first();
        $rumahTangga = Category::where('slug', 'rumah-tangga')->first();

        $products = [
            ['name' => 'Headphone Bluetooth X1', 'category_id' => $elektronik->id, 'price' => 350000, 'discount_price' => 299000, 'stock' => 25, 'weight' => 250, 'is_featured' => true],
            ['name' => 'Power Bank 10000mAh', 'category_id' => $elektronik->id, 'price' => 175000, 'stock' => 40, 'weight' => 200],
            ['name' => 'Kaos Polos Cotton Combed', 'category_id' => $fashionPria->id, 'price' => 85000, 'stock' => 100, 'weight' => 150],
            ['name' => 'Celana Chino Slim Fit', 'category_id' => $fashionPria->id, 'price' => 220000, 'discount_price' => 180000, 'stock' => 30, 'weight' => 300, 'is_featured' => true],
            ['name' => 'Set Panci Anti Lengket', 'category_id' => $rumahTangga->id, 'price' => 450000, 'stock' => 15, 'weight' => 2000],
        ];

        foreach ($products as $i => $p) {
            Product::updateOrCreate(
                ['sku' => 'SKU-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                array_merge($p, [
                    'slug' => Str::slug($p['name']),
                    'description' => 'Deskripsi lengkap untuk '.$p['name'].'. Produk berkualitas dengan garansi resmi.',
                    'status' => 'active',
                ])
            );
        }
    }
}
