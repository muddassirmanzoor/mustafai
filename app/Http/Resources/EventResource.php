<?php
namespace App\Http\Resources;
use App\Http\Controllers\Hijri\HijriDateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class EventResource extends JsonResource
{
    public function toArray($request)
    {
        if(!request()->type == 'single'){
            $startDateHijri = new HijriDateTime(date('Y-m-d', strtotime($this->start_date_time)));
        }else{
             $sessions = SessionResource::collection($this->sessions);
             $images = EventImagesResource::collection($this->images);
        }
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'location' => $this->location,
            'startDate' => $this->start_date_time,
            'endDate' => $this->end_date_time,
            'images' => EventImagesResource::collection($this->images)
        ];
        if (!request()->type == 'single') {
            $data['startDateHijri'] = $startDateHijri;
        }else{
            $data['sessions'] = $sessions;
            $data['images'] = $images;
        }
        return $data;
    }


}
