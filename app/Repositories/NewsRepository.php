<?php

namespace App\Repositories;


use App;
use App\Http\Resources\HeadlineResource;
use App\Models\Admin\Headline;
use Illuminate\Support\Facades\Validator;

class NewsRepository implements NewsRepositoryInterface
{

    /**
     *Getting news with headline
     */
    public function getNews($request)
    {
        $lang = $request->lang;
        $query = array_merge(getQuery($lang, ['title']), ['id', 'content', 'reporter_name', 'content_english', 'content_urdu', 'reporting_city', 'reporting_date_time']);
        $headlineresources = Headline::select($query);
        if ($request->has('id')) {
            $headlineresources = $headlineresources->where('id', $request->input('id'))->first();
            if ($lang == 'english') {
                $headlineresources->content = $headlineresources->content_english;
            }
            if ($lang == 'urdu') {
                $headlineresources->content = $headlineresources->content_urdu;
            }
            $data = new HeadlineResource($headlineresources);
        } else {
            $headlineresources = $headlineresources->where('status', 1)->orderBy('headline_order')->get();
            foreach ($headlineresources as $item) {
                if ($lang == 'english') {
                    $item->content = $item->content_english;
                }
                if ($lang == 'urdu') {
                    $item->content = $item->content_urdu;
                }
            }
            $data = HeadlineResource::collection($headlineresources);
        }

        return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => $data,
        ], 200);
    }
}
