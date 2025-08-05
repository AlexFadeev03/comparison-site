<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all()->map(function($c) {
            // Підібрати іконку для кожної категорії за назвою
            $icons = [
                'Smartphones' => '📱',
                'Laptops' => '💻',
                'Headphones' => '🎧',
                'Cameras' => '📷',
                'Smartwatches' => '⌚️',
            ];
            $icon = $icons[$c->name] ?? '📦';
            return [
                'id' => $c->id,
                'name' => $c->name,
                'icon' => $icon,
            ];
        });
        $subcategories = Subcategory::all()->map(function($s) {
            // Іконки для всіх
            $icons = [
                // Smartphones
                'Android' => '🤖',
                'iOS' => '🍏',
                'Foldables' => '📱',
                // Laptops
                'Ultrabooks' => '💻',
                'Gaming' => '🎮',
                'Business' => '🏢',
                // Headphones
                'In-Ear' => '🎧',
                'Over-Ear' => '🎧',
                'Wireless' => '📶',
                // Smartwatches
                'Fitness' => '🏃‍♂️',
                'Classic' => '⌚️',
                'Kids' => '🧒',
                // Cameras
                'Mirrorless' => '📷',
                'DSLR' => '📸',
                'Action' => '🎥',
            ];
            $icon = $icons[$s->name] ?? '🧩';
            return [
                'id' => $s->id,
                'category_id' => $s->category_id,
                'name' => $s->name,
                'description' => $s->description,
                'icon' => $icon,
            ];
        });

        $trendingProducts = Product::with(['subcategory.category'])
            ->withAvg('ratings as avg_rating', 'value')
            ->withCount('ratings')
            ->orderByDesc('avg_rating')
            ->orderByDesc('ratings_count')
            ->orderByDesc('id')
            ->take(10)
            ->get();
        return view('home', compact('categories', 'subcategories', 'trendingProducts'));
    }
}
