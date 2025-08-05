<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subcategory;
use App\Models\Product;

class Category extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, Subcategory::class);
    }
}
