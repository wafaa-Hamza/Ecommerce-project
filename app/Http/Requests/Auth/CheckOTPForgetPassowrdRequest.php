<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CheckOTPForgetPassowrdRequest extends FormRequest
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
            'email' => ['required_without:phone', 'string', 'email' , 'exists:users,email'],
            'phone' => ['required_without:email', 'string', 'phone' , 'exists:users,phone'],
            'otp' => ['required', 'string', 'digits:6'],
        ];
    }

}
