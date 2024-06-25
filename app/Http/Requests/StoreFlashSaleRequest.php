<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlashSaleRequest extends FormRequest
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
            'expires_at' => 'required|date_format:Y-m-d H:i:s A|after:now',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id|distinct',
            'items.*.discount' => 'required|integer|min:1',
            'items.*.image' => 'required|image|max:2048',
        ];
    }
}
