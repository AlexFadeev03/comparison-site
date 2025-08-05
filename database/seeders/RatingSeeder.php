<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Rating;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = \App\Models\User::pluck('id')->toArray();
        $productIds = \App\Models\Product::pluck('id')->toArray();
        $used = [];
        foreach ($userIds as $userId) {
            $userRated = [];
            for ($i = 0; $i < 10; $i++) {
                do {
                    $productId = $productIds[array_rand($productIds)];
                } while (in_array($productId, $userRated));
                $userRated[] = $productId;
                \App\Models\Rating::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'value' => rand(1, 5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
