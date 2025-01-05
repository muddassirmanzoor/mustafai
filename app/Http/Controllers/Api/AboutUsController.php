<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AboutUsRepositoryInterface;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    /**
     *get about us data
    */ 
    public function aboutUs(Request $request,AboutUsRepositoryInterface $aboutUs){
        return $aboutUs->getAboutUs($request);
    }
}
