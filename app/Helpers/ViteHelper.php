<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

class ViteHelper {
    public static function vite_assets(): HtmlString
    {
        // $devServerIsRunning = false;
        
        // if (app()->environment('local')) {
        //     try {
        //         Http::get("http://localhost");
        //         $devServerIsRunning = true;
        //     } catch (Exception) {
        //     }
        // }
        
        // if ($devServerIsRunning) {
        //     return new HtmlString(<<<HTML
        //         <script type="module" src="http://localhost/@vite/client"></script>
        //         <script type="module" src="http://localhost/resources/js/app.js"></script>
        //     HTML);
        // }
        
        $manifest = json_decode(file_get_contents(
            public_path('build/manifest.json')
        ), true);
        
        // dd($manifest);

        return new HtmlString(<<<HTML
            <script type="module" src="/build/{$manifest['resources/js/app.js']['file']}"></script>
            <link rel="stylesheet" href="/build/{$manifest['resources/js/app.js']['css'][0]}">
        HTML);
    }
}