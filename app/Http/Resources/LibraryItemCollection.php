<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LibraryItemCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $count =  $this->libraries()->count();
        return [
            'libraryTypeId'    => $this->id,
            'libraryTypeName'  => $this->title,
            'libraryImage'     =>  getS3File($this->icon),
            // 'libraryItemCount' => $count,
        ];
    }
}
