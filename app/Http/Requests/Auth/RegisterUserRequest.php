<?php

namespace App\Http\Requests\Auth;

use App\Http\Controllers\AuthController;
use App\Rules\MinMaxWordsRule;
use App\Rules\PhoneValidation;
use Illuminate\Foundation\Http\FormRequest;
// use Propaganistas\LaravelPhone\Rules\Phone;

class RegisterUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string' , 'between:1,255'],
            'password' => 'required|string|min:8|max:25',
            'email' => 'required|email|unique:users',
            'phone' => ['required'],
            'image' => 'image:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
