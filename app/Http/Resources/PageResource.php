<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class PageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' =>$this->url,
            'image'=>(isset($this->image) && !empty($this->image)) ? getS3File($this->image) : asset('assets/home/images/al-mustafa-logo.png')
        ];
    }
}
