<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HeadlineResource extends JsonResource
{
        public function toArray($request)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->content); // Suppress warnings for invalid HTML
        
        // Extract image URLs
        $images = [];
        foreach ($dom->getElementsByTagName('img') as $img) {
            $images[] = $img->getAttribute('src');
        }

        // Strip all HTML tags except plain text
        $textContent = strip_tags($this->content);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'reporterName' => $this->reporter_name,
            'reportingCity' => $this->reporting_city,
            'text' => $textContent,
            'images' => $images,
            'reportingDateTime' => $this->reporting_date_time,
        ];
    }


}
