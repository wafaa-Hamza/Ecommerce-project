<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneValidation implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = "/^(010|011|012|015)\d{8}$/";

        if (! preg_match($pattern, $value)) {
            $fail('The Phone must be a valid Egyptian phone number.');
        }
    }
}
