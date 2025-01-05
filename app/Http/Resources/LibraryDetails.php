<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LibraryDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // if (empty($this->img_thumb_nail)) {
        //     $imgThumbnail = libDummyThumbnail($this->type_id, $this->file);
        // } else {
        //     $imgThumbnail = asset($this->img_thumb_nail);
        // }
        return [
            'id'         => $this->id,
            'image'      => $this->img_thumb_nail,
            'title'      => $this->title,
            'content'    => $this->content,
            'parentId'   =>$this->parent_id,
            'typeId'     => $this->type_id,
            // 'documentUrl'    =>  asset($this->file),
        ];
    }
}
