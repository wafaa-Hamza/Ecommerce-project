<?php

namespace App\Http\Requests\Product;

use App\Rules\EmptyWith;
use App\Rules\EmptyWithRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexProductRequest extends FormRequest
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
            'query' => 'string',
            'query_by' => 'required_with:query|string|in:name,sku',
            'sort_by' => 'string|in:id,name,price',
            'discount' => 'boolean',
            'asc' => 'boolean|required_with:sort_by',
            'per_page' => 'integer|min:1|max:30',
        ];
    }
}
