<?php
namespace App\Filters;

use Closure;

class Content
{
    public function handle($request, Closure $next)
    {
        if (! request()->has('content')) {
            return $next($request);
        }
        $q=request()->input('content');


        return $next($request)->where(function ($query) use ($q) {
            $query->where('content_english', 'like', "%" . $q . "%")->orWhere('content_urdu', 'LIKE', '%' . $q . '%')
                ->orWhere('content_arabic', 'LIKE', '%' . $q . '%');
            });
    }
}