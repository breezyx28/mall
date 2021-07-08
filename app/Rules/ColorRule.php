<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ColorRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (preg_match("/[a-zA-Z0-9]{6}/", $value)) {
            return true;
        } else if (preg_match('/rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)/', $value)) {
            return true;
        } else if (preg_match('/rgba\((\s*\d+\s*,){3}[\d\.]+\)/', $value)) {
            return true;
        } else if (preg_match('/hsl\(\s*\d+\s*(\s*\,\s*\d+\%){2}\)/', $value)) {
            return true;
        } else if (preg_match('/hsla\(\s*\d+(\s*,\s*\d+\s*\%){2}\s*\,\s*[\d\.]+\)/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'اللون غير مطابق لمعايير اللون الرمزية';
    }
}
