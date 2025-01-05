<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TestimonialRepositoryInterface;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     *get  team library data api
    */
    public function getTestimonials(Request $request,TestimonialRepositoryInterface $testimonial){
        return $testimonial->getTestimonials($request);
    }
}
