<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

class Currency {
    public static function getSign($currency)
    {
        return self::options()[$currency];
    }

    public static function options()
    {
        return [
            'UK Pound' => '£',
            'US Dollar' => '$' ,
            'BD Taka' => 'BDT',
        ];
    }
}