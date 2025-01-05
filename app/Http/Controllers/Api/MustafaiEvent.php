<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JoinEventValidation;
use App\Mail\MustafaiEventMail;
use App\Models\Admin\Admin;
use App\Models\Admin\Event;
use App\Models\Admin\EventAttende;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Setting;

class MustafaiEvent extends Controller
{
    /**
     *store event detail api
    */
    public function store(Request $request)
    {
        // dd($request->all());
        $email = $request->email;
        $email_count = DB::table('event_attendes')->where('attende_email', '=', $email)->where('event_id', '=', $request->eventId)->count();
        if ($email_count > 0) {
            return ['status' => 201];
        }
        $query = getQuery(App::getLocale(), ['title', 'content', 'location']);
        $query[] = 'start_date_time';
        $query[] = 'end_date_time';

        $mustafaiEventDetails = Event::select($query)->find($request->eventId);
        $mustafaiEventDetailsdata = Event::find($request->eventId);
        $mustafaiEventDetails['uuid'] = (string) Str::uuid();

        // $adminEmail = Admin::find(1)->value('email');
        $adminEmail = settingValue('emailForNotification');

        $this->getEventsICalObject($mustafaiEventDetails, $adminEmail);

        $details = [
            'subject' =>  "Event Joined by a User",
            'user_name' =>  "Super Admin",
            'content'  => "<p>A user email " . $request->email . "joined an event called " . $mustafaiEventDetailsdata->title_english . " .</p>",
            'links'    =>  "<a href='" . url('events?id=' . $request->eventId . '') . "'>Click here </a> to see event details.",
        ];

        // sendEmail($adminEmail, $details);
        saveEmail($adminEmail, $details);

        try {
            Mail::to($request->email)->send(new MustafaiEventMail());
            EventAttende::create(['event_id' => $request->eventId, 'attende_email' => $request->email, 'event_title' => $mustafaiEventDetails->title]);

            if ($mustafaiEventDetailsdata->has('sessions')) {
                $sessionCols = array_merge(getQuery(App::getLocale(), ['session', 'description']), ['session_start_date_time', 'session_end_date_time']);
                foreach ($mustafaiEventDetailsdata->sessions()->select($sessionCols)->get() as $session) {
                    $session['uuid'] = (string) Str::uuid();
                    $this->getEventSessionICalObject($session, $mustafaiEventDetails, $adminEmail);
                    Mail::to($request->email)->send(new MustafaiEventMail());
                }
            }

            return ['status' => 200, 'message' => 'successfully created'];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    /**
     *store join event detail api
    */
    public function joinEvent(JoinEventValidation $request)
    {
        // dd($request->all());
        $email = $request->email;
        $lang  =$request->lang;
        $email_count = DB::table('event_attendes')->where('attende_email', '=', $email)->where('event_id', '=', $request->eventId)->count();
        if ($email_count > 0) {
            return ['status' => 201,'message' => request()->lang=='english' ? 'You have already joined this event' : 'آپ پہلے ہی اس ایونٹ میں شامل ہو چکے ہیں'];
        }
        $query = getQuery($lang, ['title', 'content', 'location']);
        $query[] = 'start_date_time';
        $query[] = 'end_date_time';

        $mustafaiEventDetails = Event::select($query)->find($request->eventId);
        $mustafaiEventDetailsdata = Event::find($request->eventId);
        $mustafaiEventDetails['uuid'] = (string) Str::uuid();

        // $adminEmail = Admin::find(1)->value('email');
        $adminEmail = settingValue('emailForNotification');

        $this->getEventsICalObject($mustafaiEventDetails, $adminEmail);

        $details = [
            'subject' =>  "Event Joined by a User",
            'user_name' =>  "Super Admin",
            'content'  => "<p>A user email " . $request->email . "joined an event called " . $mustafaiEventDetailsdata->title_english . " .</p>",
            'links'    =>  "<a href='" . url('events?id=' . $request->eventId . '') . "'>Click here </a> to see event details.",
        ];

        // sendEmail($adminEmail, $details);
        saveEmail($adminEmail, $details);

        try {
            Mail::to($request->email)->send(new MustafaiEventMail());
            EventAttende::create(['event_id' => $request->eventId, 'attende_email' => $request->email, 'event_title' => $mustafaiEventDetails->title]);

            if ($mustafaiEventDetailsdata->has('sessions')) {
                $sessionCols = array_merge(getQuery($lang, ['session', 'description']), ['session_start_date_time', 'session_end_date_time']);
                foreach ($mustafaiEventDetailsdata->sessions()->select($sessionCols)->get() as $session) {
                    $session['uuid'] = (string) Str::uuid();
                    $this->getEventSessionICalObject($session, $mustafaiEventDetails, $adminEmail);
                    Mail::to($request->email)->send(new MustafaiEventMail());
                }
            }
            $successMessage='';
            $errorMessage='';
            if(request()->lang=='english'){
                $successMessage='You have sccessfully joined this event';
                $errorMessage='You have already joined this event';
            }
            else{
                $successMessage='آپ کامیابی سے اس تقریب میں شامل ہوئے ہیں';
                $errorMessage='آپ پہلے ہی اس ایونٹ میں شامل ہو چکے ہیں';
            }
            return ['status' => 200, 'message' => $successMessage];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => $errorMessage];
        }
    }
    /**
     *get Events ICal Object function
    */
    public function getEventsICalObject($mustafaiEventDetails, $adminEmail)
    {
        $start = explode(" ", $mustafaiEventDetails->start_date_time);
        $start = $start[0] . 'T' . $start[1];
        $start = str_replace('-', '', $start);
        $start = str_replace(':', '', $start);

        $end = explode(" ", $mustafaiEventDetails->end_date_time);
        $end = $end[0] . 'T' . $end[1];
        $end = str_replace('-', '', $end);
        $end = str_replace(':', '', $end);

        $summary = $mustafaiEventDetails->title;
        $icalObject = "BEGIN:VCALENDAR
        VERSION:2.0
        METHOD:PUBLISH
        PRODID:-//Charles Oduk//Tech Events//EN\n";
        $icalObject .= "BEGIN:VEVENT
        DTSTART:" . $start . "
        DTEND:" . $end . "
        DTSTAMP:" . $start . "
        SUMMARY:$summary
        DESCRIPTION:$mustafaiEventDetails->content
        UID:$mustafaiEventDetails->uuid
        STATUS:" . 'CONFIRMED' . "
        LAST-MODIFIED:" . $start . "
        LOCATION:$mustafaiEventDetails->location
        ORGANIZER;CN=:mailto:$adminEmail
        END:VEVENT\n";
        $icalObject .= "END:VCALENDAR";
        //  $icalObject = str_replace(' ', '', $icalObject);
        $fName = storage_path() . '/ical/mustafai-event.ics';
        $fp = fopen($fName, "wb");
        fwrite($fp, $icalObject);
        fclose($fp);

        return $fName;
    }
    /**
     *get Events sessions ICal Object function
    */
    public function getEventSessionICalObject($eventSession, $mustafaiEventDetails, $adminEmail)
    {
        $start = explode(" ", $eventSession->session_start_date_time);
        $start = $start[0] . 'T' . $start[1];
        $start = str_replace('-', '', $start);
        $start = str_replace(':', '', $start);

        $end = explode(" ", $eventSession->session_end_date_time);
        $end = $end[0] . 'T' . $end[1];
        $end = str_replace('-', '', $end);
        $end = str_replace(':', '', $end);

        $summary = $eventSession->session;
        $icalObject = "BEGIN:VCALENDAR
        VERSION:2.0
        METHOD:PUBLISH
        PRODID:-//Charles Oduk//Tech Events//EN\n";
        $icalObject .= "BEGIN:VEVENT
        DTSTART:" . $start . "
        DTEND:" . $end . "
        DTSTAMP:" . $start . "
        SUMMARY:$summary
        DESCRIPTION:$eventSession->description
        UID:$eventSession->uuid
        STATUS:" . 'CONFIRMED' . "
        LAST-MODIFIED:" . $start . "
        LOCATION:$mustafaiEventDetails->location
        ORGANIZER;CN=:mailto:$adminEmail
        END:VEVENT\n";
        $icalObject .= "END:VCALENDAR";
        $fName = storage_path() . '/ical/mustafai-event.ics';
        $fp = fopen($fName, "wb");
        fwrite($fp, $icalObject);
        fclose($fp);

        return $fName;
    }
}
