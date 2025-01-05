<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'iso' => $this->iso,
            'iso3' => $this->iso3,
            'countryCode' => $this->country_code,
            'phonecode' => $this->phonecode,
       
        ];
    }
}
