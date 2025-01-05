<?php
namespace App\Filters;

use App\Models\Admin\LibraryAlbum;
use Closure;
use Illuminate\Support\Facades\DB;

class Title
{
    public function handle($request, Closure $next)
    {
        // dd($request->all());
        if (! request()->has('title')) {
            return $next($request);
        }
        $q=request()->input('title');
            // return $next($request)->where('type_id',1)->orwhere('title_english','like',"%" .$q."%")->orWhere('title_urdu', 'LIKE', '%'.$q.'%')
            // ->orWhere('title_arabic', 'LIKE', '%'.$q.'%');

        return $request->where(function ($query) use ($q) {
            $query->where('title_english', 'like', "%" . $q . "%")->orWhere('title_urdu', 'LIKE', '%' . $q . '%')
                ->orWhere('title_arabic', 'LIKE', '%' . $q . '%');
            });
    
    }
}