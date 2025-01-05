<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class CmsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            // 'image' => !empty($this->icon)? asset($this->icon):'null',
        ];
    }

    public function with($request)
    {
        return [
            'status'=>1,
            'message'=>'success'
        ];
    }
}