<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Hashids;

class UserInfoResource extends JsonResource
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
            'id' => $this->id,
            'full_name' => $this->{'user_name_'.$request->lang},
            'user_name' =>availableField($this->user_name, $this->user_name_english, $this->user_name_urdu, $this->user_name_arabic) ,
            'profile_image' =>getS3File($this->profile_image),
            'is_public' =>  $this->is_public,

        ];
    }
}
