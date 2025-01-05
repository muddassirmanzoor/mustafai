<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class SessionResource extends JsonResource
{
    public function toArray($request)
    {
        $lang=request()->lang;
        return [
            'id'                    => $this->id,
            'sessionName'           => $this->{'session_'.$lang},
            'description'           => trim(strip_tags($this->{'description_'.$lang})),
            'sessionStartDateTime'  => $this->session_start_date_time,
            'sessionEndDateTime'    => $this->session_end_date_time,
            'createdAt'             => $this->created_at,
            'updateAt'              => $this->updated_at,
        ];
    }

   
}