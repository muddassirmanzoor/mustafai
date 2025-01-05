<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiSecurityMiddleware
{
    // original key = f5af989c30d8fb3c06fee0d630c075d2001110
    private string $key = 'WmpWaFpqazRPV016TUdRNFptSXpZekEyWm1WbE1HUTJNekJqTURjMVpESXdNREV4TVRBPQ==';


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
