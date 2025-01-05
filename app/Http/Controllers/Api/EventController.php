<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventValidation;
use App\Http\Resources\CmsResource;
use App\Http\Resources\EventResource;
use App\Models\Admin\Event;
use App\Models\Admin\Page;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     *get event details
    */
    public function getEventDetails(EventValidation $request)
    {
        $lang=$request->lang;
        $eventId=$request->eventId;
        $query = array_merge(getQuery($lang, ['title', 'content','location']),['id','start_date_time','end_date_time']);
        $event = Event::select($query)->where('id',$eventId)->first();
        $content = strip_tags($event->content);
        $event->setAttribute('content',$content);
        request()->merge(['type' => 'single']);
        return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => new EventResource($event),
        ], 200);
    }
    /**
     *get all events api
    */
    public function getAllEvents(Request $request)
    {
        // dd($request);
        $lang=$request->lang;
        $query = array_merge(getQuery($lang, ['title', 'content','location']),['id','start_date_time','end_date_time']);
        $events = Event::select($query)->where('status',1)
        // ->get()
        ->latest()
        ->paginate($request->limit ?? 8);
        $events = $events->map(function($event) {
            $event->setAttribute('content', strip_tags($event->content));
            return $event;
        });
        return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => EventResource::collection($events),
        ], 200);
    }

}
