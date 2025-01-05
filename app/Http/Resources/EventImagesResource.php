<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class EventImagesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'imageUrl' => !empty($this->image)?getS3File($this->image):null,
     
        ];
    }

   
}