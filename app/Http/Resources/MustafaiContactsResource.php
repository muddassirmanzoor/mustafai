<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HeadlineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'phone' => $this->title,
            'whatsapp' => $this->reporter_name,
            'email' => $this->reporting_city,
            'address' => $this->content,
        ];
    }
}
