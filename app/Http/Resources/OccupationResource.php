<?php
namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class OccupationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'  => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'url'=>url('api/professions/'.$this->slug),
            'home_url'=>url('api/home/professions/'.$this->slug),
        ];
    }


}
