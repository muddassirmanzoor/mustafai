<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'username' => $this->user_name,
            'full_name'=>$this->full_name,
            'userId' => $this->id,
            'profile' => getS3File($this->profile_image),
            'userResume' => $this->resume,
            'accessToken' => $this->token
        ];
    }

    public function with($request)
    {
        if(request()->lang=='english'){
            $sucessMessage='successfully login';
        }
        else{
            $sucessMessage='کامیابی سے لاگ ان ہو گیا';
        }
        return [
            'status' => 1,
            'message' => $sucessMessage
        ];
    }
}
