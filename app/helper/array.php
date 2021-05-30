<?php

namespace App\Helper;

class ValidateArray
{
    public static function parse($input)
    {
        if ($input == null) {
            return $input;
        }

        $output = json_decode($input);

        if (is_array($output)) {
            return $output;
        }

        return new \Exception("The value is not array string  !!", 1);
    }

    public static function stringify($input)
    {
        if ($input == null) {
            return $input;
        }

        if (is_array($input)) {
            return json_encode($input);
        }
        return new \Exception("The value is not string array !!", 1);
    }
}
