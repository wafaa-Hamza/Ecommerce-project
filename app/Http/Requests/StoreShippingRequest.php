<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShippingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    private $citits = [
        "Alexandria",
        "Aswan",
        "Asyut",
        "Beheira",
        "Beni Suef",
        "Cairo",
        "Dakahlia",
        "Damietta",
        "Faiyum",
        "Gharbia",
        "Giza",
        "Ismailia",
        "Kafr el-Sheikh",
        "Luxor",
        "Matrouh",
        "Minya",
        "Monufia",
        "New Valley",
        "North Sinai",
        "Port Said",
        "Qalyubia",
        "Qena",
        "Red Sea",
        "Sharqia",
        "Sohag",
        "South Sinai",
        "Suez"
    ];

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
            'type' => 'required|string|in:Category,Equal,Product,City',
            'value' => ['required', 'string' ,
                            Rule::when( 
                                $this->type == 'Category', 
                                'exists:categories,id', 
                            ), 
                            Rule::when( 
                                $this->type == 'Equal', 
                                'integer', 
                            ), 
                            Rule::when( 
                                $this->type == 'Product', 
                                'exists:products,id', 
                            ), 
                            Rule::when( 
                                $this->type == 'City', 
                                "string|in:".implode(',', $this->citits),
                            ), 
                        ],
            'price' => 'required|integer|min:0|max:999999',
        ];
    }
}
