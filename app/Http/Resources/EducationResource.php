<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'institute_english' => $this->institute_english,
            'institute_urdu' => $this->institute_urdu,
            'institute_arabic' => $this->institute_arabic,
            'education_english' => $this->degree_name_english,
            'education_urdu' => $this->degree_name_urdu,
            'education_arabic' => $this->degree_name_arabic,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'date' => date('Y',strtotime($this->start_date)) .' - '. date('Y',strtotime($this->end_date)),
        ];
    }
}
