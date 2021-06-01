<?php

namespace App\Helper;

class CalcPercent
{
    public static function percent(float $value, int $percent)
    {

        $result = $value * ($percent / 100);
        return $value - $result;
    }
}
