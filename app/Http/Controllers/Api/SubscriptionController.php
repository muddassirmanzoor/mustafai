<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Bank;
use App\Models\Admin\BankAccount;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\PaymentMethodDetail;
use App\Models\Admin\UserAccount;
use App\Models\User;
use App\Models\Admin\UserSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helper\ImageOptimize;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\Designation;
use App\Models\Admin\Notification;
use App\Models\Admin\Role;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    const SUBSCRIPTION_MODULE_ID = 4;
    /**
     *get user subscriptions list api
     */
    public function userSubscriptions(Request $request)
    {
        $user = User::find($request->userId);
        $userSubscriptions = UserSubscription::where('user_id', $request->userId)->where('is_trial_period', 0)->orderBy('created_at', 'DESC')->get()
            ->each(function ($userSubscription) {
                $status = '';
                if ($userSubscription->is_paid == 1) {
                    $status = 'Paid';
                } elseif ($userSubscription->is_paid == 2) {
                    $status = 'Admin Relief';
                } else {
                    $status = 'Pending';
                }
                $userSubscription->status = $status;
                $userSubscription->subscription_start_date = Carbon::createFromTimestamp($userSubscription->subscription_start_date)->format('d-m-Y');
                $userSubscription->subscription_end_date = Carbon::createFromTimestamp($userSubscription->subscription_end_date)->format('d-m-Y');
            });

        foreach ($userSubscriptions as $record) {
            if ($user->designation_id) {
                $find = Designation::find($user->designation_id);
                if ($find) {
                    $record->amount = $find->subscription_charges;
                }
            } elseif ($user->role_id) {
                $find = Role::find($user->role_id);
                if ($find) {
                    $record->amount = $find->subscription_charges;
                }
            } else {
                $record->amount = 300;
            }
        }
        return response()->json(['status' => 1, 'message' => 'success', 'data' => $userSubscriptions]);
    }
    /**
     *get subscription detail api
     */
    public function paySubscription(Request $request)
    {
        if (!have_permission('Pay-Now-My-Subscription'))
            access_denied();
        $data = [];

        $subscription = UserSubscription::with('user_account')->find($request->subscriptionId);

        $bankCols = array_merge(getQuery(app()->getLocale(), ['name', 'short_description']), ['id']);
        $bankAccountCols = array_merge(getQuery(app()->getLocale(), ['account_title']), ['id', 'bank_id', 'branch_number', 'iban_number', 'account_number']);

        $bankAccounts = BankAccount::query()
            ->select($bankAccountCols)
            ->where('module_id', self::SUBSCRIPTION_MODULE_ID)
            ->where('status', 1)
            ->with(['bank' => fn($bank) => $bank->select($bankCols)])
            ->get();

        $data['subscription'] = $subscription;
        $data['bankAccounts'] = $bankAccounts;

        return response()->json(['status' => 1, 'message' => 'success', 'data' => $data]);
    }
    /**
     *save subscription detail api
     */
    public function payUserSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mustafai_account_id' => 'required',
            'account_title' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'reciept' => 'required',
            'branch_code' => 'nullable',
            'iban_number' => 'nullable',
            'subscription_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Fail',
                'data'    =>  $validator->errors()->toArray(),

            ], 200);
        }

        $userAccountData = array_merge($request->only(['account_title', 'account_number', 'branch_code', 'iban_number', 'bank_name', 'is_allow']), ['user_id' => auth()->id()]);

        $isUserAccount = UserAccount::query()
            ->where('bank_name', $request->bank_name)
            ->where('account_title', $request->account_title)
            ->where('account_number', $request->account_number)
            ->first();

        $userAccount = null;

        if (!$isUserAccount) {
            $userAccount = UserAccount::create($userAccountData);
        }

        $path = ImageOptimize::improve($request->reciept, 'user-subscription-reciepts');

        $subscription = UserSubscription::find($request->subscription_id);

        $subscription->update([
            'reciept' => $path,
            'mustafai_account_id' => $request->mustafai_account_id,
            'user_account_id' => $userAccount ? $userAccount->id : $isUserAccount->id,
            'is_user_submit' => 1
        ]);

        $user = User::find($subscription->user_id);

        $notification = Notification::create([

            'title' => $user->user_name_english . ' has buy subscription',

            'title_english' => $user->user_name_english . ' has buy subscription',

            'title_urdu' => (($user->user_name_urdu != '' || $user->user_name_urdu != null) ? $user->user_name_urdu : $user->user_name) . '  نے رکنیت خرید لی ہے ',

            'title_arabic' => (($user->user_name_arabic != '' || $user->user_name_arabic != null) ? $user->user_name_arabic : $user->user_name) . ' نے رکنیت خرید لی ہے ',

            'link' => url('admin/users/' . $user->id . '/subscription'),

            'module_id' => 3,

            'right_id' => 11,

            'ip' => request()->ip()

        ]);

        $admin = Admin::first();



        $admin->notifications()->attach($notification->id);


        return response()->json([
            'status'  => 1,
            'message' => 'Paid Successfully!',

        ], 200);
    }
}
