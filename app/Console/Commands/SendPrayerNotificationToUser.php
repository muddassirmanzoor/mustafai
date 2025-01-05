<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Helper\namazHelper;
use App\Services\FirebaseNotificationService;
use File;
use Illuminate\Support\Facades\Log;

class SendPrayerNotificationToUser extends Command
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
    protected $firebaseNotificationService;

    public function __construct(FirebaseNotificationService $firebaseNotificationService)
    {
        parent::__construct();
        $this->firebaseNotificationService = $firebaseNotificationService; // Inject service
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Define chunk and batch sizes
        $chunkSize = 999;
        $batchSize = 1000;

        // Process users in chunks
        User::whereNotNull('fcm_token')->chunk($chunkSize, function ($users) use ($batchSize) {
            $tokensWithMessages = []; // Updated: Array to store tokens and their specific messages

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
                            'Fajr',
                            'Dhuhr',
                            'Asr',
                            'Maghrib',
                            'Isha',
                        ];

                        // Create a new array containing only the specific prayer timings
                        $filteredPrayerTimings = [];
                        foreach ($specificPrayerTimes as $prayerName) {
                            if (isset($prayerTimings->$prayerName)) {
                                $filteredPrayerTimings[$prayerName] = $prayerTimings->$prayerName;
                            }
                        }

                        // Determine the current time
                        $currentDateTime = now();
                        $currentTime = $currentDateTime->format('H:i');

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

                            if ($timeDiffInMinutes < 15) {
                                // Format the prayer time to AM/PM
                                $dateTime = \DateTime::createFromFormat('H:i', strtok($nextPrayerTime, ' '));
                                $formatedPrayerTime = $dateTime->format('h:i A');

                                if($user->lang == 'english'){
                                    $message = "Time for $nextPrayerName prayer is approaching. Prepare for $nextPrayerName at $formatedPrayerTime ";
                                }else{
                                    $message = "نماز $nextPrayerName کا وقت قریب ہے۔ $nextPrayerName کی تیاری کریں۔ وقت: $formatedPrayerTime";
                                }
                                // Prepare the notification message
//                                $message = "Time for $nextPrayerName prayer is approaching. Prepare for $nextPrayerName at $formatedPrayerTime ";

                                // Store the token and its specific message
                                $tokensWithMessages[$user->fcm_token] = $message; // Updated: Store each message with its token

                                // Update user's last notification time
                                $user->update(['last_cron_notification_time' => now()]);
                            }
                        }
                    }
                }
            }

            // Send notifications in batches
            foreach (array_chunk(array_keys($tokensWithMessages), $batchSize) as $tokenBatch) {
                $messages = array_intersect_key($tokensWithMessages, array_flip($tokenBatch));

                foreach ($messages as $token => $message) {
                    $this->firebaseNotificationService->sendNotification('Namaz Time', $message, [$token], [], 1); // Send each message with its specific token
                }
            }
        });
    }
}
