<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $min = $this->input('min_price');
            $max = $this->input('max_price');
            if ($min !== null && $max !== null && $min > $max) {
                $validator->errors()->add('min_price', 'Min price must be less than or equal to max price');
            }
        });
    }
}
