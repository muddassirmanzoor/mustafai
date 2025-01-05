<?php

namespace App\Helper;

use File;
use Illuminate\Support\Facades\Http;
use \Carbon\Carbon;

class namazHelper
{
    public static function fileExists($file)
    {
        if (file_exists(public_path('/files/json/' . $file))) {
            return true;
        } else {
            return false;
        }

    }

    public static function createFile($params)
    {
        $response = Http::withoutVerifying()->get('https://api.aladhan.com/v1/calendarByCity?city=' . $params['city'] . "&month=" . $params['month'] . "&year=" . $params['year'] . "&country=" . $params['country'] . "&method=1" . "&school=1");
        $jsonData = $response->json();
        $fileStorePath = public_path($params['path']);
        $createFile = File::put($fileStorePath, json_encode(($jsonData)));
        if ($createFile) {
            return json_decode(File::get(public_path($params['path'])));
        } else {
            return false;
        }

    }
    public static function ActivePrayers($namazTime)
    {
        $now = \Carbon\Carbon::now()->toDateString(); // get current time
        $mytime = \Carbon\Carbon::now()->toDateTimeString();
        $namazTime = new Carbon($now . explode(' ', $namazTime)[0]);
        $existing_time=$namazTime->addMinute(25);
        // $end = new Carbon('2018-10-05 17:00:09');
        $end_time = new Carbon($mytime);
        if ($end_time->diffInMinutes($existing_time) <= 20 && $end_time->diffInMinutes($existing_time) > 0) {
            return true;
        } else {
            return false;
        }

    }

}
