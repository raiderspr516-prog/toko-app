<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Elektronik', 'Fashion Pria', 'Fashion Wanita', 'Rumah Tangga', 'Olahraga', 'Kesehatan & Kecantikan'] as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => 'active']
            );
        }
    }
}
