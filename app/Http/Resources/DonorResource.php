<?php
namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class DonorResource extends JsonResource
{
    public function toArray($request)
    {
        if (empty($this->user_id)) {
            $profile_img = getS3File($this->image);
        } else {
            $profile_img = getS3File($this->user->profile_image);
        }
        return [
            'id'  => $this->id,
            'city' => $this->city ? $this->city->{'name_'.$request->lang} : null,
            'donorName' => $this->full_name,
            'bloodGroup' => $this->blood_group,
            'contactNumber' => $this->phone_number,
            'dob' => $this->dob,
            'age' => Carbon::parse($this->dob)->age,
            'image' => !empty($profile_img) ? $profile_img : null,
        ];
    }


}
