<?php

namespace App\Http\Requests\HomeCard;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeCardRequest extends FormRequest
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
            "image" => "required|image|max:2048",
            "home_group_id" => "required|integer|min:1|exists:home_groups,id",
            'category_id' => 'required|integer|min:1|exists:categories,id',
        ];
    }
}
