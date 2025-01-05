<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $allowedIps = ['','139.135.63.6']; // List of allowed IPs

        if (!in_array($request->ip(), $allowedIps)) {
            // return $next($request);
            echo $request->ip();
            abort(403, 'Access denied');
        }

        return $next($request);
    }
    
}


// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;

// class BlockIpMiddleware
// {
//     // set IP addresses
//     // public $blockIps = ['202.166.170.106', '182.191.113.19', '127.0.0.1','192.168.99.195','192.168.99.122','192.168.99.190','192.168.99.56','119.155.17.112','202.166.170.106','39.55.254.138'];
   

//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
//      * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
//      */
//     public function handle(Request $request, Closure $next)
//     {
//         $allowedIPs = explode(',', env('ALLOWED_IPS'));
      
//         if (in_array($request->ip(), $allowedIPs)) {
//             return $next($request);
//         }
//         else{
//             access_denied();
//         }

//     }
// }

