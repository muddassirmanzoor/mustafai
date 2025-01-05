<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class TestimonialResourceCollection extends ResourceCollection
{
    public $collects = TestimonialResource::class;
    public function toArray($request)
    {
        return [
            'data' => $this->collection,

        ];
    }
}