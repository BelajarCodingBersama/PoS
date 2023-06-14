<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Product One',
            'slug' => Str::slug('Product One'),
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt quisquam voluptates, rerum cumque a totam similique unde in maiores fugit ipsam, perspiciatis, minus officia nostrum sit vitae distinctio architecto animi at doloremque accusantium magnam fugiat laborum. Minima assumenda iure ipsum sed molestias esse, cum, odit sapiente ducimus dolorem molestiae hic.',
            'price' => 50000,
            'amount' => 100,
            'product_type_id' => 1
        ]);
    }
}
