<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productTypes = [
            [
                'name' => 'T-shirt',
                'slug' => Str::slug('T-shirt')
            ],
            [
                'name' => 'Shirt',
                'slug' => Str::slug('Shirt')
            ],
            [
                'name' => 'Sweater',
                'slug' => Str::slug('Sweater')
            ],
            [
                'name' => 'Dress',
                'slug' => Str::slug('Dress')
            ],
            [
                'name' => 'Suit',
                'slug' => Str::slug('Suit')
            ],
            [
                'name' => 'Jacket',
                'slug' => Str::slug('Jacket')
            ],
            [
                'name' => 'Shorts',
                'slug' => Str::slug('Shorts')
            ],
            [
                'name' => 'Jeans',
                'slug' => Str::slug('Jeans')
            ],
        ];

        foreach ($productTypes as $productType) {
            ProductType::create($productType);
        }
    }
}
