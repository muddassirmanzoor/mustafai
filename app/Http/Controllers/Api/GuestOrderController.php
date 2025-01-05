<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Darryldecode\Cart\Cart;
use App\Http\Requests\GuestCartValidation;
use App\Models\User;
use App\Models\Store\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;
use App\Http\Resources\OrderListingResource;
use App\Http\Resources\CartListingResource;
use App\Models\Admin\OrderPaymentDetail;
use App\Models\Admin\Product;
use App\Models\Admin\Admin;
use App\Models\Admin\Setting;

class GuestOrderController extends Controller
{
    /**
     *add to cart session base
    */
    public function addToCartSession(GuestCartValidation $request)
    {
        $product = Product::find($request->productId);
        if (!$product) {
            abort(404);
        }
        \Cart::add([
                'id' => $request->productId,
                'name' => $product->name_english,
                'price' => $request->unitPrice,
                'quantity' => $request->quantity,
                'attributes' => array('subTotal' => $product->price * $request->quantity),
                'associatedModel' => $product
        ]);
        $cartCollection = \Cart::getContent();
        $data = $cartCollection->count();
        if ($cartCollection) {
            $response['status'] = 1;
            $response['count'] =$data;
            $response['cart'] =  $cartCollection;
            $response['message'] = __('app.added-into-cart');
        } else {
            $response['status'] = 0;
            $response['cartId'] = $cartCollection;
            $response['message'] = __('app.something-went-wrong');
        }
        return response()->json($response);
    }
    /**
     *get cart details
    */
    public function getCartGuest()
    {
        $cartItems = \Cart::getContent();
        // dd($cartItems);
        $data['cartItems'] = $cartItems;
        $data['shipment_rate'] = ShipmentCharge::first('shipment_rate');
        return response()->json([
            'status'=> 1,
            'message'=>'Data found!!',
            'data'=>$data
        ]);
    }
    /**
     *place order for guest user
    */
    public function placeOrderGuest(Request $request){
        // dd(json_encode($request->all()));
        $data=[];
        $input = $request->all();
        // dd($input);
        $userID = (Auth::check()) ? Auth::user()->id : null;
        $orderModal = new Order();

        if ($request->hasFile('receipt')) {
            // $reciept = $request->file('receipt');
            // $fileName = 'reciept' . time() . rand(1, 100) . '.' . $reciept->extension();
            // if ($reciept->move(public_path('orders-receipts'), $fileName)) {
            //     $recieptPath = 'orders-receipts/' . $fileName;
            //     $input['receipt'] = $recieptPath;
            // }
            $recieptPath = uploadS3File($request , "orders-receipts" ,"receipt","order-recipts",$filename = null);
            //dd($recieptPath);
            $input['receipt'] = $recieptPath;
        }
        $orderModal->sub_total = $request->subTotal;
        $orderModal->shipment_amount = $request->shippingAmount;
        $orderModal->grand_total = $request->grandTotal;
        $orderModal->billing_email = $request->billingEmail;
        $orderModal->billing_phone_number = $request->billingPhoneNumber;
        $orderModal->billing_user_name = $request->billingUsername;
        $orderModal->billing_address = $request->billingAddress;
        $orderModal->billing_city_id = $request->billingCityId;
        $orderModal->billing_country_id = $request->billingCountryId;
        $orderModal->is_billing_address_is_shipping = $request->isBillingAddressIsShipping;
        $orderModal->shipping_email = $request->shippingEmail;
        $orderModal->shipping_phone_number = $request->shippingPhoneNumber;
        $orderModal->shipping_user_name = $request->shippingUsername;
        $orderModal->shipping_address = $request->shippingAddress;
        $orderModal->shipping_city_id = $request->shippingCityId;
        $orderModal->shipping_country_id = $request->shippingCountryId;
        $orderModal->payment_method_id=isset($input['paymentMethodID'])?$input['paymentMethodID'] :'';
        $orderModal->product_payment_method_id=isset($input['productPaymentMethodId']) ?$input['productPaymentMethodId'] : '';
        $orderModal->receipt =isset($input['receipt']) ? $input['receipt'] : '';
        $orderModal->user_id  = $request->userId;

        if ($orderModal->save()) {
            if (!empty($input['paymentDetails']) && isset($input['paymentDetails'])){
                $payment_detail=json_decode($request->paymentDetails);
                if (!empty($payment_detail)) {
                    $modelRecieptDetails = new OrderPaymentDetail();
                    $modelRecieptDetails->order_id = $orderModal->id;
                    $modelRecieptDetails->payment_method_id = $payment_detail->paymentMethodId;
                    $modelRecieptDetails->product_payment_method_id = $payment_detail->productPaymentMethodId;
                    $modelRecieptDetails->payment_method_detail_id = $payment_detail->paymentMethodDetailId;
                    $modelRecieptDetails->payment_method_field_value = $payment_detail->paymentMethodFieldValue;
                    $modelRecieptDetails->save();
                }
            }

            $items = [];
            $productName = '';
            $loop = 1;
            $productItems=json_decode($request->productItems);
            if(!empty($productItems)){
                foreach ($productItems as $key => $item) {
                    $items[$key]['order_id'] = $orderModal->id;
                    $items[$key]['user_id'] = $userID;
                    $items[$key]['product_id'] = $item->id;
                    $items[$key]['quantity'] = $item->quantity;
                    $items[$key]['unit_price'] = $item->price;
                    $items[$key]['total'] = $item->totalPrice;
                    if ($loop == 1) {

                        $productName .= Product::where('id', $item->id)->value('name_english');
                    } else {
                        $productName .= "," . Product::where('id', $item->id)->value('name_english');
                    }
                    $loop++;
                }

                $orderModal->orderItems()->createMany($items);
            }
            $data['order_id'] = $orderModal->id;
        }
        $userName = $request->billingUsername;
        //user mail
        $details = [
            'subject' => "Order Delivered Successfully",
            'user_name' => "" . $userName . "",
            'content' => "<p>Your order has been delivered successfully.Thanks for purchasing the product from Mustafai Store.</p>",
            'links' => "<a href='" . url('user/mustafai-store') . "'>Click Here </a>to buy more products",
        ];
        try {
            sendEmail($request->billingEmail, $details);
        } catch (Exception $e) {
            //catch code
        }
        //admin mail
        $admin_details = [
            'subject' => "Order Received",
            'user_name' => "Super Admin",
            'content' => "<p> A user named " . $userName . "  placed an order via Mustafai Store .</p>",
            'links' => "<a href='" . url('admin/orders') . "'>Click here</a> to log in and view order details.",
        ];
        // $email = Admin::find(1)->value('email');
        $email = settingValue('emailForNotification');
        saveEmail($email, $admin_details);
            return response()->json(
            [
                'status'  => 1,
                'message' => "Order Placed.",
                'data' => $data
            ]);

    }
}
