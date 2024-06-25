<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidateAttribute implements ValidationRule
{

    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if ($this->type === 'Color' && ! Validator::make(['color' => $value], ['color' => 'hex_color'])->passes()) {
            $fail("The $attribute must be a valid hex color code.");
        } elseif ($this->type === 'Size' && !in_array($value, ['S', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', 'XXXXXL'])) {
            $fail("The $attribute must be one of: S, L, XL, XXL, XXXL, XXXXL, XXXXXL.");
        }

    }
}
