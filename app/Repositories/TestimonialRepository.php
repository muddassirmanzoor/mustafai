<?php

namespace App\Repositories;

use App\Http\Resources\TestimonialResourceCollection;
use App\Models\Admin\Testimonial;


class TestimonialRepository implements TestimonialRepositoryInterface
{

    /**
     *Getting Testimonials
    */
    public function getTestimonials($request)
    {
        $lang=$request->lang;
        $query = array_merge(getQuery($lang, ['name','title','message']),['id','date_time','link','image','is_featured']);
        $dataTestimonial = Testimonial::select($query)->where('status',1)->orderBy('testimonial_order')->paginate($request->limit ?? 10);
        return new TestimonialResourceCollection($dataTestimonial->items());
    }
}
