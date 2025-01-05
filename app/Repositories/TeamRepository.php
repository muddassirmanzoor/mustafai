<?php

namespace App\Repositories;


use App;
use App\Http\Resources\HeadlineResource;
use App\Http\Resources\SectionResourceCollection;
use App\Http\Resources\TestimonialResource;
use App\Http\Resources\TestimonialResourceCollection;
use App\Models\Admin\Headline;
use App\Models\Admin\Section;
use App\Models\Admin\Testimonial;
use Illuminate\Support\Facades\Validator;

class TeamRepository implements TeamRepositoryInterface
{

    /**
     *Getting Team members
    */
    public function ourTeam($request)
    {

        $lang=$request->lang;
        $query = array_merge(getQuery($lang, ['name']),['id']);
        $dataSection = Section::select($query)->where('status',1)
        ->latest()
        ->get();
        // ->paginate($request->limit ?? 10);
        // return  SectionResourceCollection::collection($dataSection);
        return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => SectionResourceCollection::collection($dataSection),
        ], 200);
    }
}
