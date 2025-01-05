<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonationList;
use App\Http\Resources\DonationPaymentMethodResource;
use App\Http\Resources\PaymentMethodResource;
use App\Models\Admin\Donation;
use App\Models\Admin\DonationCategory;
use App\Models\Admin\DonationPaymentMethod;
use App\Models\Admin\DonationReceipt;
use App\Models\Admin\DonationRecieptDetail;
use App\Models\Admin\PaymentMethod;
use App\Models\User;
use App\Repositories\DonationpaymentHistoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\ProductPaymentMethod;

class DonationController extends Controller
{
    /**
     *get donation details
    */
    public function donationDetails()
    {
        $details = PaymentMethod::with(['paymentDetails', 'donationPaymentMethods.donationPaymentMethodDetails.paymentMethodDetail'])->get();
        return PaymentMethodResource::collection($details);
    }
    /**
     *store donation details api
    */
    public function doDonate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            // 'user_id' => "required",
            'receipt' => 'required',
            'donation_id' => 'required|integer',
            "donation_payment_method_id" => "required|integer|exists:donation_payment_methods,id",
            "payment_method_id" => "required|integer|exists:payment_methods,id",
        ]);

        $response = [];
        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = $validator->errors()->toArray();
        } else {
            // dd($request->method_details);
            $path = '';
            if ($request->hasFile('receipt')) {
                // $reciept = $request->file('receipt');
                // $fileName = 'reciept' . time() . rand(1, 100) . '.' . $reciept->extension();
                // if ($reciept->move(public_path('images/receipts'), $fileName)) {
                //     $recieptPath = 'images/receipts/' . $fileName;
                // }
                // if ($request->receipt->move(public_path('images/receipts'), $fileName)) {
                    // $path =  'images/receipts/' . $fileName;
                    $recieptPath = uploadS3File($request , "images/receipts" ,"receipt","donations-recipts",$filename = null);
                    //dd($recieptPath);
                    $input = $request->only(['email', 'donation_payment_method_id', 'payment_method_id', 'donation_id', 'user_id', 'name', 'phone', 'address', 'amount', 'receipt', 'status']);
                    // $input['receipt'] = $path;
                    $input['receipt'] = $recieptPath;
                    $input['status'] = 0;
                    $model = new DonationReceipt();
                    $model->fill($input);
                    $isSave = $model->save();
                    $reciept_id = $model->id;

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
                        if(request()->lang=='english'){
                            $successMessage='Your donation successfully added.';
                        }
                        else{
                            $successMessage='!آپ کا عطیہ جمع ہو گیا ہے';
                        }
                        $response['status'] = 1;
                        $response['message'] = $successMessage;
                        $details = [
                            'user_name' =>  "Sir/Mam",
                            'content'  => "<p>You Are successfully send Donation record Amount of " . $input['amount'] . ".</p>",
                            'links'    =>  "<a href='" . url('/') . "'>Click Here Go To Mustafai Portral</a>",
                        ];
                        if (!empty($input['user_id']) && isset($input['user_id'])) {
                            $email = User::find($input['user_id'])->value('email');
                        } else {
                            $email = $input['email'];
                        }
                        try {
                            \Mail::to($email)->send(new \App\Mail\CommonMail($details));
                        } catch (Exception $e) {
                            //catch code
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = 'Something went wrong.';
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'Issue in upload Receipt.';
                }
            // } else {
            //     $response['status'] = 0;
            //     $response['message'] = 'Receipt Required.';
            // }
        }
        return   $response;
    }
    /**
     *Get Donation Payment History
    */
    public function getDonationPaymentHistory(Request $request, DonationpaymentHistoryRepositoryInterface $donation)
    {
        
        return $donation->getDonationPaymentHistory($request);
    }

    /**
     *Get Donations List
    */
    public function getDonationList(Request $request)
    {
        

        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            // 'offset' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'validation fails',
                'data' => $validator->errors()->toArray(),

            ], 200);
        } else {
            $categoryId = $request->categoryId;
            $donationData = Donation::where('status', 1)
            // ->where('id', '>', $request->offset)
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('donation_category_id', $categoryId);
            })
            ->latest()
            ->paginate($request->limit ?? 10);
            // ->limit($request->limit)->get();
            if (!$donationData->isEmpty()) {
                $offset = $donationData[$donationData->keys()->last()]->id;
                $donationdatajson = DonationList::collection($donationData);
                return response()->json([
                    'status'     => 1,
                    'message'    => 'success',
                    'offset'     => $offset,
                    'data'       => $donationdatajson,
                ], 200);
            } else {
                return response()->json([
                    'status' => 1,
                    'message' => 'No record found',

                ], 200);
            }
        }
    }
    /**
     *get Payment Methods
    */
    public function paymentMethods(){
        $paymentMethods=PaymentMethod::all();
        if (!$paymentMethods->isEmpty()) {
            return response()->json([
                'status'     => 1,
                'message'    => 'success',
                'data'       => $paymentMethods,
            ], 200);
        } else {
            return response()->json([
                'status' => 1,
                'message' => 'No record found',

            ], 200);
        }
    }

    /**
     *get Payment Methods Details
    */
    public function getPaymentMethodDetails(Request $request)
    {
        $paymentMethodId  = $request->payment_method_id;
        if (isset($request->product) && $request->product == 'product') {
            $data['product'] = $request->product;
            $data['productPaymentMethods'] = ProductPaymentMethod::with('productPaymentMethodDetails')->where('product_payment_methods.payment_method_id', $paymentMethodId)->get();
        } else {
            $data['product'] = '';
            $data['donationPaymentMethods'] = DonationPaymentMethod::with('donationPaymentMethodDetails')->where('donation_payment_methods.payment_method_id', $paymentMethodId)->get();
        }
        if (!empty($data)) {
            return response()->json([
                'status'     => 1,
                'message'    => 'success',
                'data'       => $data,
            ], 200);
        } else {
            return response()->json([
                'status' => 1,
                'message' => 'No record found',

            ], 200);
        }

    }
    /**
     *get Donation categories
    */
    public function getDonationCategories(Request $request)
    {
        $lang = $request->input('lang');
        $query = array_merge(getQuery($lang, ['name']),['id','status']);
        $categories = DonationCategory::select($query)->where('status', 1)->get()->toArray();
         return response()->json([
            'status'  => 1,
            'message' =>"success",
            'data' =>$categories,
         ]);
    }
}
