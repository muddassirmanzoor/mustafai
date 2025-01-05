<?php

namespace App\Http\Resources;

use App\Models\Admin\Occupation;
use Illuminate\Http\Resources\Json\JsonResource;
use Mockery\Exception;

class UserListingResource extends JsonResource
{

    public function toArray($request)
    {

       
        return [
            'user_id' => $this->id,
            'username' => $this->user_name,
            'occupation' => $this->occupationUser !== null ? $this->occupationUser->title : null,
            'profileImage' => getS3File($this->profile_image),
            'address' => $this->address,
            'phone' => $this->phone_number,
            'email' => $this->email,
            'name' => $this->full_name,
            'profile' => $this->is_public == 1 ? "public" : "private",



        ];
    }
}
