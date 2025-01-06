<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Auth;

class LogAdminLoginInfo
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
        // return $next($request);

        $response = $next($request);

        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $admin->ip_address = $request->ip();
            $admin->platform = Agent::platform();
            $admin->device = Agent::device();
            $admin->browser = Agent::browser();
            $admin->save();
        }

        return $response;
    }
}
