<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // Smartphones
            ['category_id' => 1, 'name' => 'Android', 'description' => 'Android-powered smartphones', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'name' => 'iOS', 'description' => 'Apple iPhones', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'name' => 'Foldables', 'description' => 'Foldable smartphones', 'created_at' => now(), 'updated_at' => now()],
            // Laptops
            ['category_id' => 2, 'name' => 'Ultrabooks', 'description' => 'Slim and light laptops', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Gaming', 'description' => 'High-performance gaming laptops', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Business', 'description' => 'Laptops for professionals', 'created_at' => now(), 'updated_at' => now()],
            // Headphones
            ['category_id' => 3, 'name' => 'In-Ear', 'description' => 'Compact in-ear headphones', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'name' => 'Over-Ear', 'description' => 'Comfortable over-ear headphones', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'name' => 'Wireless', 'description' => 'Wireless headphones', 'created_at' => now(), 'updated_at' => now()],
            // Smartwatches
            ['category_id' => 4, 'name' => 'Fitness', 'description' => 'Fitness tracking smartwatches', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'name' => 'Classic', 'description' => 'Classic style smartwatches', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'name' => 'Kids', 'description' => 'Smartwatches for kids', 'created_at' => now(), 'updated_at' => now()],
            // Cameras
            ['category_id' => 5, 'name' => 'Mirrorless', 'description' => 'Mirrorless cameras', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 5, 'name' => 'DSLR', 'description' => 'DSLR cameras', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 5, 'name' => 'Action', 'description' => 'Action cameras', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('subcategories')->insert($subcategories);
    }
}
