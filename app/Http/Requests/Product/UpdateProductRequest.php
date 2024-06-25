<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Validator;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'short_description' => 'string|max:1000',
            'long_description' => 'string|max:6000',
            'quantity' => 'integer|min:1',
            'live' => 'boolean',
            'expires_at' => 'date_format:Y-m-d H:i:s|after:now',
            'price' => ['required_with:discount','max:999998.99' , 'numeric' , 'decimal:0,2'],
            'discount' => ['required_with:price','max:999999.99' , 'numeric' , 'decimal:0,2'],
            'sku' => ['string','max:255',Rule::unique('products')->ignore($this->product)],
            'category_id' => 'integer|min:1|exists:categories,id',
            'image' => 'max:1',
            'image.*' => 'mimes:jpg,png|max:2048',
            'additional_images' => 'max:20',
            'additional_images.*' => 'image|mimes:jpg,png|max:2048',
            'colors' => 'array|max:20',
            'colors.*' => 'string|max:255|hex_color',
            'sizes' => 'array|distinct',
            'sizes.*' => ['string', 'max:255', 'distinct', 'regex:^(S|M|L|X*L)$^i'],
        ];
    }
}
