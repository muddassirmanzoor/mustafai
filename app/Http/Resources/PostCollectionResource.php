<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
class PostCollectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            // 'status' => 1,
            // 'message' => 'posts fetched successfully',
            'data' => parent::toArray($request),
            'postId'=>$this->id,
            'images'=>getS3File($this->images),
            
        ];
    }
}
