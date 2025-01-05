<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'institute_name_english' => $this->experience_company_english,
            'institute_name_urdu' => $this->experience_company_urdu,
            'institute_name_arabic' => $this->experience_company_arabic,
            'starting_date' => $this->experience_start_date,
            'end_date' => $this->experience_end_date,
            'location_english' => $this->experience_location_english,
            'location_urdu' => $this->experience_location_urdu,
            'location_arabic' => $this->experience_location_arabic,
            'is_currently_working' => $this->is_currently_working,
            'title_english' => $this->title_english,
            'title_urdu' => $this->title_urdu,
            'title_arabic' => $this->title_arabic,


        ];
    }
}
