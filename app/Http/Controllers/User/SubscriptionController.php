<?php

namespace App\Http\Controllers\User;

use App;
use App\Helper\ImageOptimize;
use App\Http\Controllers\Controller;
use App\Models\Admin\Bank;
use App\Models\Admin\BankAccount;
use App\Models\Admin\Designation;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\PaymentMethodDetail;
use App\Models\Admin\Role;
use App\Models\Admin\UserAccount;
use App\Models\User;
use App\Models\Admin\UserSubscription;
use DataTables;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    const SUBSCRIPTION_MODULE_ID = 4;


    /**
     *Get List Of Order History
     */
    public function mySubscriptions(Request $request)
    {
        if (!have_permission('View-My-Subscription')) {
            // dd("ok");
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        $data = [];
        if ($request->ajax()) {
            $db_record = UserSubscription::where('user_id', auth()->user()->id)->where('is_trial_period', 0)->orderBy('created_at', 'DESC')->get();
            foreach ($db_record as $record) {
                if (auth()->user()->designation_id) {
                    $find = Designation::find(auth()->user()->designation_id);
                    if ($find) {
                        $record->amount = $find->subscription_charges;
                    }
                } elseif (auth()->user()->role_id) {
                    $find = Role::find(auth()->user()->role_id);
                    if ($find) {
                        $record->amount = $find->subscription_charges;
                    }
                } else {
                    $record->amount = 300;
                }
            }
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('start_date', function ($row) {
                $date = $row->subscription_start_date;
                return (!empty($date)) ? date('d-m-Y', $date) : 'N/A';
            });

            $datatable = $datatable->editColumn('end_date', function ($row) {
                $date = $row->subscription_end_date;
                return (!empty($date)) ? date('d-m-Y', $date) : 'N/A';
            });

            $datatable = $datatable->editColumn('reciept', function ($row) {
                $reciept = $row->reciept;
                return (!empty($reciept)) ? '<a target="_blank" href="' . getS3File($reciept) . '">' . __('app.view-receipt') . '</a>' : 'N/A';
            });
            $datatable = $datatable->editColumn('is_paid', function ($row) {
                if ($row->is_paid == 1) {
                    $is_paid = '<span class="badge badge-success" style="background-color: #009a71;">' . __('app.paid') . '</span>';
                } elseif ($row->is_paid == 2) {
                    $is_paid = '<span class="badge badge-warning" style="background-color: #ffc107;">' . __('app.admin-relief') . '</span>';
                } else {
                    $is_paid = '<span class="badge badge-danger" style="background-color: red;">' . __('app.pending') . '</span>';
                }
                return $is_paid;
            });

            $datatable = $datatable->editColumn('statusHidden', function ($row) {
                if ($row->is_paid == 1) {
                    $is_paid = 'Paid';
                } elseif ($row->is_paid == 2) {
                    $is_paid = 'Admin Relief';
                } else {
                    $is_paid = 'Pending';
                }
                return $is_paid;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                if ($row->is_paid == 0 && $row->is_user_submit == 0) {
                    $actions = '<span class="actions">';
                    if (have_permission('Pay-Now-My-Subscription')) {
                        $actions .= '<a class=" ml-2" href="javascript:void(0)" title="Pay Now" onclick="paySubscriptionFee(' . $row->id . ')">
                            <span class="btn btn-primary">
                                <i class="far fa-plus-square"></i>
                            </span>
                        </a>';
                    }
                    $actions .= '</span>';
                    return $actions;
                }
            });

            $datatable = $datatable->rawColumns(['start_date', 'end_date', 'reciept', 'is_paid', 'statusHidden', 'action',]);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('user.my-subscriptions-list', $data);
    }

    /**
     *getting subscription details
     */
    public function paySubscription($subscriptionId)
    {
        if (!have_permission('Pay-Now-My-Subscription'))
            access_denied();

        $subscription = UserSubscription::with('user_account')->find($subscriptionId);
        if ($subscription) {
            if (auth()->user()->designation_id) {
                $find = Designation::find(auth()->user()->designation_id);
                if ($find) {
                    $subscription->amount = $find->subscription_charges;
                }
            } elseif (auth()->user()->role_id) {
                $find = Role::find(auth()->user()->role_id);
                if ($find) {
                    $subscription->amount = $find->subscription_charges;
                }
            } else {
                $subscription->amount = 300;
            }
        }
        $bankCols = array_merge(getQuery(app()->getLocale(), ['name', 'short_description']), ['id']);
        $bankAccountCols = array_merge(getQuery(app()->getLocale(), ['account_title']), ['id', 'bank_id', 'branch_number', 'iban_number', 'account_number']);

        $bankAccounts = BankAccount::query()
            ->select($bankAccountCols)
            ->where('module_id', self::SUBSCRIPTION_MODULE_ID)
            ->where('status', 1)
            ->with(['bank' => fn($bank) => $bank->select($bankCols)])
            ->get();


        $html = view('user.partials.subscriptions.pay-subscription-partial', compact('subscription', 'bankAccounts'))->render();

        return response()->json(['status' => 1, 'html' => $html]);
    }

    /**
     *Pay for subscription
     */
    public function payUserSubscription(Request $request)
    {
        $request->validate([
            'mustafai_account_id' => 'required',
            'account_title' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'reciept' => 'required',
            'branch_code' => 'nullable',
            'iban_number' => 'nullable',
            'subscription_id' => 'required'
        ]);

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

        return redirect()->back()->with('success', 'Paid Successfully!');
    }
}
