<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Donation;
use App\Models\Admin\DonationPaymentMethod;
use App\Models\Admin\DonationPaymentMethodDetails;
use App\Models\Admin\DonationReceipt;
use App\Models\Admin\DonationRecieptDetail;
use App\Models\Admin\Notification;
use App\Models\Admin\ProductPaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function __construct()
    {
    }
    
    /**
     *Donate or store donations
    */
    public function donate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'receipt' => 'required',
        ]);

        $response = [];
        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = 'Validation Failed.';
        } else {

            $userName = (Auth::check()) ? auth()->user()->user_name : 'Guest User';

            $path = '';
            if ($request->receipt) {
                $fileName = 'receipt' . time() . '.' . $request->receipt->extension();
                if ($request->receipt) {
                    $path =  uploadS3File($request , "images/receipts" ,"receipt","donation",$filename = null);
                    $input = $request->only(['email', 'donation_payment_method_id', 'payment_method_id', 'donation_id', 'user_id', 'name', 'phone', 'address', 'amount', 'receipt', 'note', 'status']);
                    $input['receipt'] = $path;
                    $input['status'] = 0;
                    $model = new DonationReceipt();
                    $model->fill($input);
                    $isSave = $model->save();
                    $donation_english = Donation::find($model->donation_id)->value('title_english');
                    $reciept_id = $model->id;
                    if (isset($input['user_id'])) {
                        $email = User::where('id', $input['user_id'])->value('email');
                    } else {
                        $email = $input['email'];
                    }
                    foreach ($request->method_details as $key => $val) {
                        if (!empty($val)) {
                            $modelRecieptDetails = new DonationRecieptDetail();
                            $modelRecieptDetails->donation_receipt_id = $reciept_id;
                            $modelRecieptDetails->payment_method_id = $input['payment_method_id'];
                            $modelRecieptDetails->donation_payment_method_id = $input['donation_payment_method_id'];
                            $modelRecieptDetails->payment_method_detail_id = $key;
                            $modelRecieptDetails->payment_method_field_value = $val;
                            $modelRecieptDetails->save();
                        }
                    }
                    if ($isSave) {
                        $response['status'] = 1;
                        $response['message'] = __('app.donation-msg');

                        $donationTitle = Donation::find($request->donation_id)->title_english;

                        // send notification
                        $admin = Admin::first();
                        $notification = Notification::create([
                            'title' => $userName . ' has donate to ' . Str::words($donationTitle, 4, '...'),
                            'title_english' => $userName . ' has donate to ' . Str::words($donationTitle, 4, '...'),
                            'title_urdu' =>  $userName . 'نے' . Str::words($donationTitle, 4, '...') . 'کو عطیہ کیا ہے',
                            'title_arabic' => $userName . Str::words($donationTitle, 4, '...') . 'تبرع ل',
                            'link' => route('donations.index'),
                            'module_id' => 21,
                            'right_id' => 66,
                            'ip' => request()->ip()
                        ]);
                        $admin->notifications()->attach($notification->id);

                        $details = [
                            'subject'   =>   'Donation Submitted Successfully',
                            'user_name' =>   $userName,
                            'content'   =>  "<p>Thanks for your contributing Amount of " . $input['amount'] . " in donation " . $donation_english . ".</p>",
                            'links'     =>  "<a href='" . url('/login') . "'>Click Here</a> to contribute in more donations",
                        ];
                        $details_admin = [
                            'subject' =>  "Donation Received",
                            'user_name' =>  " Super Admin",
                            'content'  => "<p> A user named " .  $userName . " made a donation at " . $donation_english . " .</p>",
                            'links'    =>  "<a href='" . url('admin/donations') . "'>Click here</a> to log in and view donation details.",
                        ];

                        // sendEmail(Admin::first()->email, $details_admin);
                        $adminEmail = settingValue('emailForNotification');
                        saveEmail($adminEmail, $details_admin);
                        // sendEmail($email, $details);
                        saveEmail($email, $details);
                        // log user activity

                    } else {
                        $response['status'] = 0;
                        $response['message'] = 'Something went wrong.';
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'Issue in upload Receipt.';
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'Receipt Required.';
            }
        }
        echo json_encode($response);
        exit();
    }

    /**
     *Get donation payment methods
    */
    public function getDonationPaymentMethod(Request $request)
    {
        $paymentMethodId  = $request->payment_method_id;
        if (isset($request->product) && $request->product == 'product') {
            $data['product'] = $request->product;
            $data['productPaymentMethods'] = ProductPaymentMethod::with('productPaymentMethodDetails')->where('product_payment_methods.payment_method_id', $paymentMethodId)->get();
        } else {
            $data['product'] = '';
            $data['donationPaymentMethods'] = DonationPaymentMethod::with('donationPaymentMethodDetails')->where('donation_payment_methods.payment_method_id', $paymentMethodId)->get();
        }
        $html =  (string) View('home.partial.donation-payment-methods', $data);
        echo $html;
    }
}
