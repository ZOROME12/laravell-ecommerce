<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User; // <-- added
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Product::create([
            'name' => 'Sample Product 1',
            'description' => 'This is a sample product description.',
            'price' => 29.99,
            'stock' => 100,
            'image' => 'https://via.placeholder.com/150',
            'category_id' => 1, // Jerseys
        ]);

        Product::create([
            'name' => 'Sample Product 2',
            'description' => 'Another sample product.',
            'price' => 49.99,
            'stock' => 50,
            'image' => 'https://via.placeholder.com/150',
            'category_id' => 2, // Shirts
        ]);
    }
}
