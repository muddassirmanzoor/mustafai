<?php

namespace App\Repositories;


use App;
use App\Http\Resources\HeadlineResource;
use App\Http\Resources\TestimonialResource;
use App\Http\Resources\TestimonialResourceCollection;
use App\Models\Admin\Headline;
use App\Models\Admin\Testimonial;
use Illuminate\Support\Facades\Validator;

class AboutUsRepository implements AboutUsRepositoryInterface
{

    /**
     *Getting Testimonials details api
    */
    public function getAboutUs($request){
        $lang=$request->lang;
        $query = array_merge(getQuery($lang, ['name','title','message']),['id','date_time','link','image','is_featured']);
        $dataTestimonial = Testimonial::select($query)->where('status',1)->orderBy('testimonial_order')->paginate(10);
        return new TestimonialResourceCollection($dataTestimonial);
    }
}
