<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use \Carbon\Carbon;

class PrayerController extends Controller
{
    /**
     *get daily prayer times api
    */
    public function getDailyPrayerTimes(Request $request){
        $ip = $request->ip();
        $ip = ($ip == '127.0.0.1') ? '182.179.185.30' : $ip;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $ipdat='';
        if (($request->has('latitude') && !empty($request->latitude)) && ($request->has('longitude') && !empty($request->longitude))) {
            $url = "https://api.bigdatacloud.net/data/reverse-geocode-client?latitude={$latitude}&longitude={$longitude}&localityLanguage=en";
            $ipdat = @json_decode(file_get_contents(
                $url
            ));
        }
        $ipdata = @json_decode(file_get_contents(
            "http://ip-api.com/json/" . $ip
        ));
        $city = !empty($ipdat->city) ? $ipdat->city : $ipdata->city;
        $country = !empty($ipdat->countryName) ? $ipdat->countryName : $ipdata->country;
        $date=date("d");
        $now = new \DateTime('now');
        $month = $now->format('m');
        $year = $now->format('Y');
        $date = Carbon::now(); // Get the current date and time
        $formattedDate = $date->format('d-m-Y'); // Format the date as DD-MM-YYYY

        $response = Http::withoutVerifying()->get('http://api.aladhan.com/v1/timingsByCity/'.$formattedDate."?city=" . $city . "&month=" . $month . "&year=" . $year . "&country=" . $country . "&method=1" . "&school=1");
        $jsonData = $response->json();
        $jsonData['data']['city']=$city;
        $jsonData['data']['country']=$country;
        return response()->json($jsonData);

    }
    /**
     *get monthly prayer times api
    */
    public function getMonthlyPrayerTimes(Request $request){
        $ip = $request->ip();
        $ip = ($ip == '127.0.0.1') ? '182.179.185.30' : $ip;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $ipdat='';
        if (($request->has('latitude') && !empty($request->latitude)) && ($request->has('longitude') && !empty($request->longitude))) {
            $url = "https://api.bigdatacloud.net/data/reverse-geocode-client?latitude={$latitude}&longitude={$longitude}&localityLanguage=en";
            $ipdat = @json_decode(file_get_contents(
                $url
            ));
        }
        $ipdata = @json_decode(file_get_contents(
            "http://ip-api.com/json/" . $ip
        ));
        $city = !empty($ipdat->city) ? $ipdat->city : $ipdata->city;
        $country = !empty($ipdat->countryName) ? $ipdat->countryName : $ipdata->country;
        $date=date("d");
        $now = new \DateTime('now');
        $month = $now->format('m');
        $year = $now->format('Y');
        $response = Http::withoutVerifying()->get('https://api.aladhan.com/v1/calendarByCity/'.$year."/". $month."?city=" . $city . "&country=" . $country . "&method=1" . "&school=1");
        $jsonData = $response->json();
        return response()->json($jsonData);
    }
}
