<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => ['string' , 'between:1,255'],
            'nick' => ['string' , 'between:1,255'],
            'gender' => ['string' , 'in:Male,Female'],
            'address' => ['string' , 'between:1,255'],
            'phone' => 'phone',
            'image' => 'image|mimes:jpeg,jpg,png|max:2048',
            'roles' => 'array',
            'roles.*' => 'string|distinct|in:over_view,category,product,order,stock,shipping,admin,setting',
        ];
    }
}
