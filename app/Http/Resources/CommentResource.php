<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Hashids;

class CommentResource extends JsonResource
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
            'commentId' => $this->id,
            'userId' => $this->user_id,
            'personImage' => getS3File($this->user->profile_image),
            'personUsername' =>  $this->user->user_name,
            'commentDescription' => $this->body,

        ];
    }
}
