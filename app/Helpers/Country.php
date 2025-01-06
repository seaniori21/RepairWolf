<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;
use Auth;

class Country {
    public static function fullName($short)
    {
        return self::options()[$short];
    }

    public static function options()
    {
        return [
            'UK' => 'United Kingdom',
        ];
    }
}