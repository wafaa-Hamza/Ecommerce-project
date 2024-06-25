<?php

namespace App\Http\Requests\Order;

use App\Rules\PhoneValidation;
use App\Rules\ValidCityShipping;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'email' => 'required|email',
            'phone' => ['required' , new PhoneValidation],
            'address' => 'required|string|max:255',
            'address_2' => 'string|max:255',
            'name' => 'required|string|max:255',
            'city' => ['required','string','max:255' , new ValidCityShipping()],
            'postal_code' => 'required|digits:5|integer',
            'pay_on_delivery' => 'required|boolean',
        ];
    }
}
