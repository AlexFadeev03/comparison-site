<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:20',
            'description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'price' => 'required|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'pros' => 'nullable|string|max:200',
            'cons' => 'nullable|string|max:200',
            'features' => 'nullable|string|max:300',
        ];
    }
}
