<?php

namespace App\Http\Resources;

use App\Http\Resources\PageResource;
use App\Models\Admin\Page;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HomeResource extends JsonResource
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toArray($request)
    {
        $dataArr = [];
        foreach ($this->data as $key => $value) {
            $dataArr[$key] = $value;
        }
        return [
            'breakingNews' => $dataArr['headline'],
            'videoURL' => url($dataArr['viedoUrl']['option_value']),
            'sliders' => $dataArr['slider_data'],
            'pages' => PageResource::collection($dataArr['page_data']),
            'events' => EventResource::collection($dataArr['evnets_data']),
            'occupations' => OccupationResource::collection($dataArr['occupations']),
            'ceoMessage' => $dataArr['ceo_message'],
            'ceoMessageReadMore' =>url('ceo-message'),

        ];
    }

    public function with($request)
    {
        return [
            'status' => 1,
            'message' => 'success',
        ];
    }
}
