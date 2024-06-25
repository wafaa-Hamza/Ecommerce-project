<?php

namespace App\Rules;

use App\Models\Shipping;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCityShipping implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Shipping::where([['type', 'City'],['value', $value]])->exists()){
            $fail("The $attribute must be a valid city in shipping cities.");
        }
    }
}
