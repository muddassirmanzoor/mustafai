<?php

namespace App\Console\Commands;

use App\Models\Admin\UserSubscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Helper\namazHelper;
use App\Services\FirebaseNotificationService;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendPaymentNotificationToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:payment-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Payment Notification to users';

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
        Log::info('Cron is running for payment notifications');

        // Calculate the target date, start of day, and end of day for 5 days from now
        $targetDate = Carbon::now()->addDays(5)->startOfDay()->timestamp;
        $startOfDay = time();
        $endOfDay = Carbon::createFromTimestamp($targetDate)->endOfDay()->timestamp;
        // Query to get the latest subscription for each user
        $latestSubscriptions = UserSubscription::select(DB::raw('MAX(id) as latest_id'))
            ->groupBy('user_id');

        // Get subscriptions that match the criteria
        $subscriptions = UserSubscription::with('user')
            ->joinSub($latestSubscriptions, 'latest_subscriptions', function ($join) {
                $join->on('user_subscriptions.id', '=', 'latest_subscriptions.latest_id');
            })
            ->whereBetween('subscription_end_date', [$startOfDay, $endOfDay])
            ->whereHas('user', function ($query) {
                $query->whereNotNull('fcm_token');
            })
            ->where('is_paid', 1)
            ->get();


        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions ending in 5 days.');
            Log::info('No subscriptions ending in 5 days.');
        } else {
            // Collect notifications with individual messages per user
            $notifications = [];

            foreach ($subscriptions as $subscription) {
                $date = Carbon::createFromTimestamp($subscription->subscription_end_date)->toDateString();
                $userName = $subscription->user->full_name;
                $message = "Dear $userName, your subscription is going to end on $date."; // Personalized message

                $notifications[] = [
                    'user_id' => $subscription->user_id,
                    'fcm_token' => $subscription->user->fcm_token,
                    'message' => $message,
                    'title' => 'Subscription Payment',
                    'type' => 'payment-notification',
                ];

                $this->info($message);
            }

            // Batch notifications into chunks of 1000
            $chunks = array_chunk($notifications, 1000);

            foreach ($chunks as $chunk) {
                // Map tokens to their respective messages
                $fcmData = [];
                foreach ($chunk as $notification) {
                    $fcmData[] = [
                        'token' => $notification['fcm_token'],
                        'message' => $notification['message'],
                    ];
                }

                // Send each notification with the unique message for each token
                foreach ($fcmData as $data) {
                    $this->firebaseNotificationService->sendNotification('Subscription Payment', $data['message'], [$data['token']], [], 1);
                    Log::info("Payment Notification sent to user " . $notification['user_id']);
                }
            }
        }

        return 0;
    }


    // public function handleOld()
    // {
    //     Log::info('cron is running for payment notifications');

    //     // Calculate the target date, start of day, and end of day for 5 days from now
    //     $targetDate = Carbon::now()->addDays(5)->startOfDay()->timestamp;
    //     $startOfDay = Carbon::createFromTimestamp($targetDate)->startOfDay()->timestamp;
    //     $endOfDay = Carbon::createFromTimestamp($targetDate)->endOfDay()->timestamp;

    //     // Query to get the latest subscription for each user
    //     $latestSubscriptions = UserSubscription::select(DB::raw('MAX(id) as latest_id'))
    //         ->groupBy('user_id');

    //     // Get subscriptions that match the criteria
    //     $subscriptions = UserSubscription::with('user')
    //         ->joinSub($latestSubscriptions, 'latest_subscriptions', function ($join) {
    //             $join->on('user_subscriptions.id', '=', 'latest_subscriptions.latest_id');
    //         })
    //         ->whereBetween('subscription_end_date', [$startOfDay, $endOfDay])
    //         ->whereHas('user', function ($query) {
    //             $query->whereNotNull('fcm_token');
    //         })
    //         ->where('is_paid', 1)
    //         ->get();

    //     if ($subscriptions->isEmpty()) {
    //         $this->info('No subscriptions ending in 5 days.');
    //         Log::info('No subscriptions ending in 5 days.');
    //     } else {
    //         $notifications = [];

    //         foreach ($subscriptions as $subscription) {
    //             $date = Carbon::createFromTimestamp($subscription->subscription_end_date)->toDateString();
    //             $userName = $subscription->user->full_name;
    //             $message = "Your subscription is going to end on $date.";

    //             $notifications[] = [
    //                 'user_id' => $subscription->user_id,
    //                 'fcm_token' => $subscription->user->fcm_token,
    //                 'message' => $message,
    //                 'title' => 'Subscription Payment',
    //                 'type' => 'payment-notification',
    //             ];

    //             $this->info($message);
    //         }

    //         // Batch notifications into chunks of 1000
    //         $chunks = array_chunk($notifications, 1000);

    //         foreach ($chunks as $chunk) {
    //             $fcmTokens = array_column($chunk, 'fcm_token');
    //             $message = $chunk[0]['message']; // Using the message from the first notification for the batch

    //             // FCM payload
    //             $this->firebaseNotificationService->sendNotification('Subscription', $message, $fcmTokens, [], 1); // Adjust badge as needed

    //             // sendPaymentNotificationrToUsers($fcmTokens, 'Namaz Time', $message, 'prayer-notification');

    //             foreach ($chunk as $notification) {
    //                 Log::info('Payment Notification sent to user ' . $notification['user_id']);
    //             }
    //         }
    //     }

    //     return 0;
    // }
}
