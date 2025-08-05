<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubcategoryRequest extends FormRequest
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
        ];
    }
}
