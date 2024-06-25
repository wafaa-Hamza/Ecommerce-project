<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\PhoneValidation;
use App\Services\AuthService;
use Illuminate\Foundation\Http\FormRequest;
use Jenssegers\Agent\Agent;

use function Laravel\Prompts\select;

class LoginUserRequest extends FormRequest
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
            'email' => ['required', 'string', 'email'],
            'password' => 'required|string|min:8|max:25',
        ];
    }

}
