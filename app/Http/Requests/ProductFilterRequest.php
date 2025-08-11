<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'nullable',
                'int',
                'min:1',
                'exists:' . Category::class . ',id'
            ],
            'subcategory_id' => [
                'nullable',
                'int',
                'min:1',
                'exists:' . SubCategory::class . ',id'
            ],
            'min_price' => [
                'nullable',
                'numeric',
                'min:0',
                Rule::when(
                    fn ($attributes) => $attributes->get('min_price') && $attributes->get('max_price'),
                    'lte:max_price'
                ),
            ],
            'max_price' => [
                'nullable',
                'numeric',
                Rule::when(
                    fn ($attributes) => $attributes->get('min_price') && $attributes->get('max_price'),
                    'gte:min_price'
                ),
            ],
        ];
    }

    public function messages()
    {
        return [
            'min_price.lte' => 'The :attribute field must be less than or equal to max price.',
            'max_price.gte' => 'The :attribute field must be greater than or equal to min price.',
        ];
    }
}
