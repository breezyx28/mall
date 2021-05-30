<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class categoryImageRule implements Rule
{
    private $cat_name;

    public function __construct($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function passes($attribute, $value)
    {
        $checkCatName = \App\Models\Category::whereE('name', $this->cat_name)->exists();

        if ($checkCatName) {
            return true;
        }
        return false;
    }

    public function message()
    {
        return 'الصورة موجودة مسبقا لهذا الصنف.';
    }
}
