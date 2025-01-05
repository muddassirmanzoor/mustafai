<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Helper\namazHelper;
use File;

class SendPrayerNotificationToUser_real extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:prayer-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Prayer Notification to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // \Log::info('cron is ruuning for prayers');
//        $users = User::whereNotNull('fcm_token')->get();
        $chunkSize = 999;

        User::whereNotNull('fcm_token')->chunk($chunkSize, function ($users) {
            foreach ($users as $user) {
                $currentTime = now();
                $lastNotificationDiff = strtotime($currentTime) - strtotime($user->last_cron_notification_time);
                $lastNotificationDiffInMinutes = $lastNotificationDiff / 60;

                if ($lastNotificationDiffInMinutes > 40) {
                    if (!empty($user->location_city) && !empty($user->location_country)) {
                        $date = date("d");
                        $now = new \DateTime('now');
                        $month = $now->format('m');
                        $year = $now->format('Y');
                        $file_name = $month . '-' . $year . '-' . $user->location_city . '.json';
                        $path = '/files/json/' . $file_name;

                        $file_exsist = namazHelper::fileExists($file_name);

                        if ($file_exsist) {
                            $namazData = json_decode(File::get(public_path($path)));
                            if (!empty($namazData)) {
                                $data['daily']['namazTime'] = $namazData->data[($date - 1)];
                            }
                        } else {
                            $params = array('city' => $user->location_city, 'country' => $user->location_country, 'month' => $month, 'year' => $year, 'file_name' => $file_name, 'path' => $path);
                            $createFile = namazHelper::createFile($params);
                            if (!empty($createFile)) {
                                $data['daily']['namazTime'] = $createFile->data[($date - 1)];
                            }
                        }
                        // Parse the API response and get the prayer timings
                        $prayerTimings = $data['daily']['namazTime']->timings;

                        // Define the specific prayer times you want to keep
                        $specificPrayerTimes = [
                            'Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha',
                        ];

                        // Create a new array containing only the specific prayer timings
                        $filteredPrayerTimings = [];
                        foreach ($specificPrayerTimes as $prayerName) {
                            if (isset($prayerTimings->$prayerName)) {
                                $filteredPrayerTimings[$prayerName] = $prayerTimings->$prayerName;
                            }
                        }

                        // Determine the current time
                        // $currentDateTime = now();
                        $currentDateTime = now();
                        $currentTime = $currentDateTime->format('H:i');
                        // $currentTime = '18:17';

                        // Find the next prayer time
                        $nextPrayerTime = null;
                        $nextPrayerName = null;
                        foreach ($filteredPrayerTimings as $prayerName => $prayerTime) {
                            if (strtotime($prayerTime) > strtotime($currentTime)) {
                                $nextPrayerTime = $prayerTime;
                                $nextPrayerName = $prayerName;
                                break;
                            }
                        }
                        if ($nextPrayerTime !== null) {
                            // Calculate the time difference in minutes
                            $timeDiff = strtotime($nextPrayerTime) - strtotime($currentTime);
                            $timeDiffInMinutes = $timeDiff / 60;

                            // Check if the time difference is less than 5 minutes
                            if ($timeDiffInMinutes < 15) {
                                // Original time
                                $originalTime = $nextPrayerTime;

                                // Remove the "(PKT)" part and create a DateTime object
                                $dateTime = \DateTime::createFromFormat('H:i', strtok($originalTime, ' '));

                                // Format the time as AM/PM
                                $formatedPrayerTime = $dateTime->format('h:i A');

                                if($user->lang == 'english'){
                                    $message = "Time for $nextPrayerName prayer is approaching. Prepare for $nextPrayerName at $formatedPrayerTime ";
                                }else{
                                    $message = "نماز $nextPrayerName کا وقت قریب ہے۔ $nextPrayerName کی تیاری کریں۔ وقت: $formatedPrayerTime";
                                }

//                                $message = "Time for $nextPrayerName prayer is approaching. Prepare for $nextPrayerName at $formatedPrayerTime ";
                                sendNamazNotificationrToUsers($user->id, 'Namaz Time', $message, 'prayer-notification');
                                $user->update(['last_cron_notification_time' => now()]);
                                \Log::info('prayer Notification sent to user ' . $user->full_name);
                            }
                        }
                        // else{
                        //     \Log::info('Something went wrong');
                        // }
                    }
                    // else{
                    //     \Log::info('User Location is invalid');
                    // }
                }
                // else {
                //     \Log::info('Colund not send notification again and again');
                // }
            }
            // return 0;
        });
    }
}
