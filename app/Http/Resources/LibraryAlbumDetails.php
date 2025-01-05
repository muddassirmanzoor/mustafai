<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LibraryAlbumDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id'         => $this->id,
            'image'      => $this->img_thumb_nail,
            'title'      => $this->title,
            'content'    => $this->content,
            'parentId'   => $this->parent_id,
            'typeId'     => $this->type_id,
            'created_at'  => \Carbon\Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function with($request){
        return [
            'mydata'=>"adfdsaf",
        ];
    }
}
