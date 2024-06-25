<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinMaxWordsRule implements ValidationRule
{
    private $max_words;

    private $min_words;

    public function __construct($min_words, $max_words)
    {
        $this->min_words = $min_words;
        $this->max_words = $max_words;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Use a regular expression that matches any Unicode word character
        $pattern = '/\p{L}[\p{L}\p{Mn}\p{Pd}\']*\p{L}/u';

        // Count the number of words that match the pattern
        preg_match_all($pattern, $value, $matches);
        $num_words = count($matches[0]);

        if ($num_words > $this->max_words) {
            $fail('The :attribute cannot be longer than '.$this->max_words.' words.');
        } elseif ($num_words < $this->min_words) {
            $fail('The :attribute cannot be shorter than '.$this->min_words.' words.');
        }
    }
}
