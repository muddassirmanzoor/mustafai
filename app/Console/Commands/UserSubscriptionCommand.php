<?php

namespace App\Console\Commands;

use App\Helper\UserFee;
use App\Models\Admin\UserSubscription;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UserSubscriptionCommand extends Command
{
    public $userFee, $userSubscriptionModel;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'user subscription';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->userFee = new UserFee();
        $this->userSubscriptionModel = new UserSubscription();
    }

    /**
     * Handle the command execution.
     *
     * @return void
     */
    public function handle()
    {
        // SQL query to select the latest user subscriptions for a specific user ID
        $query = "
            SELECT t1.*
            FROM user_subscriptions t1
            WHERE t1.created_at = (
                SELECT MAX(created_at)
                FROM user_subscriptions t2
                WHERE t2.user_id = t1.user_id
            )
            AND t1.subscription_end_date < UNIX_TIMESTAMP()
        ";

        // Execute the raw SQL query
        $results = \DB::select(\DB::raw($query));
        
        // Iterate over the results
        foreach ($results as $subscribedUser) {
            // Create a new subscription for each user
            $this->createSubscription($subscribedUser);
            
            // Check deferred days and update fallback role for each user
            $this->checkDeferredDays($subscribedUser);
        }
        
        // Log the information about the subscription update
        \Log::info(today()->format('d-m-Y') . ' ' . 'Subscription Update');
    }
    /**
     * Create a new subscription for the specified user.
     *
     * @param mixed $subscribedUser The user to create the subscription for.
     * @return void
     */
    public function createSubscription($subscribedUser)
    {
        // Find the user by user ID
        $user = User::find($subscribedUser->user_id);
        
        // If the user exists, create a subscription
        if ($user) {
            // Subscribe the user with parameters: user, isTrial=false, subscription data, isCron=true
            $this->userFee->subscribeUser($user, false, $subscribedUser, true);
        }
    }
    /**
     * Check deferred days and update fallback role for the specified subscription.
     *
     * @param mixed $subscription The subscription to check deferred days for.
     * @return void
     */
    public function checkDeferredDays($subscription)
    {
        // Call the method to calculate deferred days and update fallback role
        $this->userFee->calculateDeferredDaysAndUpdateFallbackRole($subscription);
    }
}
