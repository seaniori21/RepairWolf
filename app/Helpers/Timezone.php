<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;
use Auth;

class Timezone {
    public static function currentDate()
    {
        return now(Auth::user()->tz)->toDateTimeString('Y-m-d');
    }

    public static function currentDatetime()
    {
        return now(Auth::user()->tz);
    }

    public static function options()
    {
        return [
            'Europe/London',
            'Asia/Dhaka',
        ];
    }
}