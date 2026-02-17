<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoHtml implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        if ($value !== strip_tags($value)) {
            $fail('Kolom ini tidak boleh berisi tag HTML.');
        }
    }
}

