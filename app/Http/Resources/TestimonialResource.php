<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class TestimonialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => !empty($this->image)?getS3File($this->image):'',
            'name' =>  $this->name,
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
            'dateTime' => $this->date_time,
        ];
    }
}