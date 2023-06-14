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
                'name' => 'A',
                'slug' => Str::slug('A')
            ],
            [
                'name' => 'B',
                'slug' => Str::slug('B')
            ],
            [
                'name' => 'C',
                'slug' => Str::slug('C')
            ]
        ];

        foreach ($productTypes as $productType) {
            ProductType::create($productType);
        }
    }
}
