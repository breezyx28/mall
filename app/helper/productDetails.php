<?php

namespace App\Helper;

class ProducsDetails
{

    public static function details($result)
    {

        $productsPropertyList = [
            'color' => $result->whereNotNull('additional_description.color')->values()->pluck('additional_description.color'),
            'countryOfMade' => $result->whereNotNull('additional_description.countryOfMade')->values()->pluck('additional_description.countryOfMade'),
            'company' => $result->whereNotNull('additional_description.company')->values()->pluck('additional_description.company'),
            'weight' => $result->whereNotNull('additional_description.weight')->values()->pluck('additional_description.weight'),
            'expireDate' => $result->whereNotNull('additional_description.expireDate')->values()->pluck('additional_description.expireDate'),
            'price' => ['from' => $result->min('price'), "to" => $result->max('price')],
            'rate' => collect($result->whereNotNull('rate.*.rate')->pluck('rate.*.rate'))->filter(function ($value, $key) {
                return !empty($value);
            })->collapse(),
        ];

        // return final result
        return [
            'productsPropertyList' => $productsPropertyList,
            'result' => $result->values()->all()
        ];
    }
}
