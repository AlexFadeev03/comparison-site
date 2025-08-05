<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Smartphones', 'description' => 'Latest and greatest smartphones from top brands', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laptops', 'description' => 'High-performance laptops for work and play', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Headphones', 'description' => 'Best headphones for music and calls', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Smartwatches', 'description' => 'Feature-rich smartwatches for every lifestyle', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cameras', 'description' => 'Capture moments with top-rated cameras', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('categories')->insert($categories);
    }
}
