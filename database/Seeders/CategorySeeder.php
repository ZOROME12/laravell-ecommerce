<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Jerseys', 'Shirts', 'Shorts', 'Jackets', 'Others'];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
