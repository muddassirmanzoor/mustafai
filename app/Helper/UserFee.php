<?php
namespace App\Helper;
use App\Models\Admin\Setting;
use App\Models\Admin\Role;
use App\Models\Admin\Designation;
use App\Models\Admin\UnionCouncil;
use App\Models\Admin\UserSubscription;
use App\Models\User;
use Carbon\Carbon;
class UserFee
{
    public const DEFAULT_FEE = 299;
    public function monthlyFeeSetByAdmin($roleId,$user)
    {
        
        $fee = self::DEFAULT_FEE;
        if (!empty($user->designation_id)) {
            $fee = Designation::where('id', $roleId)->first()->subscription_charges ?? self::DEFAULT_FEE;
        } else {
            $fee = Role::where('id', $roleId)->first()->subscription_charges ?? self::DEFAULT_FEE;
        }
        return $fee;
    }
    public function deferredDaysSetByAdmin()
    {
        return Setting::where('option_name', 'deferred_days')->first()->option_value ?? false;
    }
    /**
     * @param User $user
     * @param $subscription
     * @return void
     */
    public function updateUserPlatformFee(User $user, $subscription): void
    {
        $user->update([
            'subscription_amount' => $subscription->amount,
            'subscription_amount_cycles' => empty($this->cycleFeeByAdmin($user)) ? 0 : $user->subscription_amount_cycles - 1
        ]);
    }
    /**
     * Subscribe a user to a new subscription plan.
     *
     * @param User $user The user to subscribe.
     * @param bool $isTrial Whether the subscription is a trial.
     * @param mixed $subscription The subscription plan.
     * @param bool $isCron Whether the subscription is created via cron job.
     * @return void
     */
    public function subscribeUser(User $user, $isTrial, $subscription, $isCron): void
    {
        // Determine the user's role ID
        $loggedInRoleId = $user->login_role_id ? $user->login_role_id : $user->role_id;
        // Check if the subscription is created via cron job
        if ($isCron) {
            // Get today's date
            $todayDate = Carbon::now();
            // Get the subscription start date and subtract one day
            $subscriptionStartDate = Carbon::createFromTimestamp($subscription->subscription_start_date);
            $subscriptionStartDate->subDays(1);
            // Calculate the number of days between the subscription start date and today's date
            $days = $subscriptionStartDate->diffInDays($todayDate);
            // Create a new subscription if the subscription is expired or unpaid
            if (property_exists($subscription, 'no_of_days') && $days > $subscription->no_of_days) {
                $subscriptionNew = UserSubscription::create([
                    'user_id' => $user->id,
                    'amount' => empty($this->cycleFeeByAdmin($user)) ? $this->monthlyFeeSetByAdmin($loggedInRoleId,$user) : $user->subscription_amount,
                    'subscription_start_date' => $this->todayDate(),
                    'subscription_end_date' => $this->afterMonth(),
                    'is_trial_period' => $isTrial ? 1 : 0,
                    'is_paid' => 0,
                    'no_of_days' => (int) $this->deferredDaysSetByAdmin()
                ]);
                // Update the user's subscription status and platform fee
                $this->updateUserPlatformFee($user, $subscriptionNew);
            }
        } else {
            // Create a new subscription with the same details if not created via cron job
            $subscriptionNew = UserSubscription::create([
                'user_id' => $user->id,
                'amount' => empty($this->cycleFeeByAdmin($user)) ? $this->monthlyFeeSetByAdmin($loggedInRoleId,$user) : $user->subscription_amount,
                'subscription_start_date' => $this->todayDate(),
                'subscription_end_date' => $this->afterMonth(),
                'is_trial_period' => $isTrial ? 1 : 0,
                'is_paid' => 0,
                'no_of_days' => (int) $this->deferredDaysSetByAdmin()
            ]);
            // Update the user's subscription status and platform fee
            $this->updateUserPlatformFee($user, $subscriptionNew);
            $this->updateUserSubscriptionStatus($user, $subscriptionNew);
        }
    }
    public function todayDate()
    {
        return strtotime(date('d-m-Y'));
    }
   /**
 * Calculate the end date after a specific number of days defined in the settings.
 *
 * @return int UNIX timestamp representing the end date
 */
    public function afterMonth()
    {
        // Retrieve the number of days from the settings, defaulting to 0 if not found
        $numberOfDays = Setting::where('option_name', 'deferred_days')->first()->option_value ?? 0;
        // Get the current date
        $currentDate = Carbon::now();
        // Calculate the end date by adding the number of days minus 1 (to exclude current day)
        $endDate = $currentDate->addDays($numberOfDays - 1);
        // Format the end date as a string
        $formattedEndDate = $endDate->toDateString();
        // Convert the formatted end date to a UNIX timestamp and return it
        return strtotime($formattedEndDate);
        // Alternative: Return the UNIX timestamp directly without formatting the end date
        // return strtotime('+ '.$afterDays. ' days');
    }
    public function cycleFeeByAdmin(User $user)
    {
        return $user->subscription_amount_cycles != 0 ? $user->subscription_amount : '';
    }
    public function updateUserSubscriptionStatus($user, $newlyCreatedSubscription)
    {
        $date = $this->todayDate();
        $subscription = $user->userSubscriptions()->where('subscription_start_date', '<=', $date)->where('subscription_end_date', '>', $date)->first();
        if (!empty($subscription)) {
            if ($subscription->is_paid == 0) {
                $this->calculateDeferredDaysAndUpdateFallbackRole($newlyCreatedSubscription);
                /* $role_id = settingValue('fall_back_role_id');
                $user->update(['subscription_fallback_role_id' => $role_id]);
                return 1;*/
            } else {
                $user->update(['subscription_fallback_role_id' => null]);
                return 1;
            }
        }
    }
    /**
     * Calculate deferred days and update fallback role for a subscription.
     *
     * @param mixed $subscription The subscription for which deferred days are to be calculated.
     * @return bool Returns true if the deferred days are calculated and fallback role is updated, otherwise false.
     */
    public function calculateDeferredDaysAndUpdateFallbackRole($subscription): bool
    {
        // Check if deferred days are set by admin
        if ($this->deferredDaysSetByAdmin() != null) {
            // Get today's date
            $todayDate = Carbon::now();
            // Find the user associated with the subscription
            $user = User::find($subscription->user_id);
            // Get the subscription start date and subtract one day
            $subscriptionStartDate = Carbon::createFromTimestamp($subscription->subscription_start_date);
            $subscriptionStartDate->subDays(1);
            // Calculate the number of days between the subscription start date and today's date
            $days = $subscriptionStartDate->diffInDays($todayDate);
            // Check if the calculated deferred days exceed the specified number of days in the subscription
            if (property_exists($subscription, 'no_of_days') && $days > $subscription->no_of_days) {

                // Get the fallback role ID from settings
                $role_id = settingValue('fall_back_role_id');
                // Update trial period access and assign fallback role
                $user->update(['subscription_fallback_role_id' => $role_id]);
                return true;
            } else {
                // Reset the fallback role ID to 0
                $user->update(['subscription_fallback_role_id' => 0]);
                return true;
            }
        }
        return false;
    }
    public static function addUnionCouncil($name,$tehsil_id,$zone_id)
    {
        $unionCouncil=UnionCouncil::where('name_english',$name)->orWhere('name_urdu',$name)->where('tehsil_id',$tehsil_id)->where('zone_id',$zone_id)->exists();
        if(isset($name)){
            if(!$unionCouncil){
                $union_counsil = new UnionCouncil();
                $union_counsil->name_english = $name;
                $union_counsil->name_urdu = $name;
                $union_counsil->tehsil_id = $tehsil_id;
                $union_counsil->zone_id = $zone_id;
                $union_counsil->status =1;
                $union_counsil->save();
                $union_counsil_id = $union_counsil->id;
            }
            else{
                $union_counsil_id=UnionCouncil::where('name_english',$name)->orWhere('name_urdu',$name)->where('tehsil_id',$tehsil_id)->where('zone_id',$zone_id)->pluck('id')->first();
            }
            return $union_counsil_id;
        }
    }
}
