<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Rating;

class Product extends Model
{
    protected $fillable = [
        'subcategory_id',
        'name',
        'image',
        'price',
        'rating',
        'pros',
        'cons',
        'features',
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function category()
    {
        return $this->hasOneThrough(Category::class, Subcategory::class, 'id', 'id', 'subcategory_id', 'category_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating(): float
    {
        return $this->ratings()->avg('value') ?? 0.0;
    }

    // Scope: filter by category
    public function scopeFilterCategory($query, $categoryId)
    {
        if ($categoryId) {
            $query->whereHas('subcategory', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        return $query;
    }

    // Scope: filter by subcategory
    public function scopeFilterSubcategory($query, $subcategoryId)
    {
        if ($subcategoryId) {
            $query->where('subcategory_id', $subcategoryId);
        }
        return $query;
    }

    // Scope: filter by price range
    public function scopeFilterPrice($query, $min, $max)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    // Scope: sort by rating
    public function scopeSortByRating($query, $direction = 'desc')
    {
        return $query->withAvg('ratings as avg_rating', 'value')->orderBy('avg_rating', $direction);
    }

    // Scope: sort by price
    public function scopeSortByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    // Scope: sort by name
    public function scopeSortByName($query, $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }
}
