<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\AllowedIpAddress;

class CheckAllowedIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedIPs = AllowedIpAddress::where('trash', 0)->pluck('ip_address')->toArray();

        // Get the user's IP address
        $userIP = $request->ip();
        // Get the user's IP address

        // Check if the user's IP is in the allowed IPs list
        if (!empty($allowedIPs) && !in_array($userIP, $allowedIPs)) {
            abort(403, 'Unauthorized. Your IP address is not allowed.');
        }
        // Check if the user's IP is in the allowed IPs list

        return $next($request);
    }
}
