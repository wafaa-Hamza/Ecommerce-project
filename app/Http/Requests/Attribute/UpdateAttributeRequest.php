<?php

namespace App\Http\Requests\Attribute;

use App\Rules\ValidateAttribute;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
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
            'type' => 'required|in:Color,Size',
            'value' => ['required', 'string' , new ValidateAttribute(request()->input('type'))],
        ];
    }
}
