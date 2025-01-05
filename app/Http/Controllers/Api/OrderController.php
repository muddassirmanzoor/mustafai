<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartValidation;
use App\Http\Requests\CmsValidation;
use App\Models\Admin\Page;
use App\Models\Admin\Product;
use App\Models\Store\Cart;
use App\Models\User;
use App\Models\Store\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\OrderListingResource;
use App\Http\Resources\CartListingResource;
use App\Models\Admin\OrderPaymentDetail;

class OrderController extends Controller
{
    /**
     *Add to cart api
    */
    public function addToCart(CartValidation $request)
    {
        $response = [];
        $input               = $request->all();
        $modal               = new Cart();
        $modal->user_id      = $input['userId'];
        $modal->unit_price   = $input['unitPrice'];
        $modal->quantity     = $input['quantity'];
        $modal->product_id   = $input['productId'];
        $modal->total        = $input['totalPrice'];
        $product             = Product::find($input['productId']);

        $exist = Cart::where(['user_id' => $input['userId'], 'product_id' => $product->id])->first();

        if (!empty($exist)) {
            $quantity = $exist->quantity + $input['quantity'];
            $total    = $exist->total + $input['totalPrice'];
            $cart     = $exist->update(['quantity' => $quantity, 'unit_price' => $input['unitPrice'], 'total' => $total]);
            $cart_id = $exist->id;
        } else {
            $cart = $modal->save();
            $cart_id = $modal->id;
        }

        if ($cart) {
            $response['status'] = 1;
            $response['count'] = $modal->getCartCounterapi($input['userId']);
            $response['cartId'] =  $cart_id;
            $response['message'] = __('app.added-into-cart');
        } else {
            $response['status'] = 0;
            $response['cartId'] = $modal->getCartCounterapi($input['userId']);
            $response['message'] = __('app.something-went-wrong');
        }
        return response()->json($response);
    }

    /**
     * GET ALL MY ORDERS
     *@param  [integer] userId
     *@return \Illuminate\Http\JsonResponse
     */
    public function getMyOrders(Request $request)
    {

        $input = $request->all();

        $validation_rules = array(
            'userId'       => 'required',

        );
        $validator = Validator::make($request->all(), $validation_rules);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()]);
        }

        if ($input["userId"]) {
            $user = User::where(['id' => $input["userId"]])->where('status', 1)->first();

            if ($user) {

                $orders = Order::with('orderPaymentDetails.paymentMethod', 'orderPaymentDetails.paymentMethodDetail', 'productPaymentMethod.productPaymentMethodDetails', 'productPaymentMethod.paymentMethod')->where(['user_id' => $user->id]);

                $total_orders =  $orders->count();
                $orders = $orders
                        ->latest()
                        ->paginate($request->limit ?? 12);

                if (!$orders->isEmpty()) {
                    return OrderListingResource::collection($orders->items())
                        ->additional([
                            'total_records' => $total_orders,
                            'message' => "Order listed successfully",
                            'status'  => 1
                        ]);
                } else {

                    return response()->json(
                        [
                            'status' => 0,
                            'data' => [],
                            'message' => "No Record Found"
                        ]
                    );
                }
            } else {
                return response()->json(['status' => 0, 'message' => "User not Exists",]);
            }
        } else {
            return response()->json(['status' => 0, 'message' =>  "Invalid User id"]);
        }
    }


    /**
     * GET MY CART
     *@param  [integer] userId
     *@return \Illuminate\Http\JsonResponse
     */
    public function getCart(Request $request)
    {
        $input = $request->all();
        $validation_rules = array(
            'userId'       => 'required',
        );
        $validator = Validator::make($request->all(), $validation_rules);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()]);
        }

        $user = User::where(['id' => $input["userId"]])->where('status', 1)->first();

        if ($user) {

            $cart = Cart::where(['user_id' => $user->id])->get();
            if (!$cart->isEmpty()) {
                $cartData = CartListingResource::collection($cart);
                return response()->json(
                    [
                        'status' => 0,
                        'data' => [
                            'products' => [
                                $cartData
                            ]
                        ],
                        'message' => "Cart listed successfully",
                        'status'  => 1
                    ]
                );
            } else {

                return response()->json(
                    [
                        'status' => 0,
                        'data' => [],
                        'message' => "No Record Found"
                    ]
                );
            }
        } else {
            return response()->json(['status' => 0, 'message' => "User not Exists",]);
        }
    }
    /**
     * Place Order for authenticated user api
     *@param  [integer] userId
     *@return \Illuminate\Http\JsonResponse
     */
    public function orderNow(Request $request)
    {
        //dd("ok");
        // dd(json_encode($request->all()));
        $input = $request->all();
        // dd($input);
        $cartIds = $input['cartIds'];
        $cartIds = explode(',', $cartIds);
        $userID = $input['userId'];
        $orderModal = new Order();

        if ($request->hasFile('receipt')) {

            // $reciept = $request->file('receipt');
            // $fileName = 'reciept' . time() . rand(1, 100) . '.' . $reciept->extension();
            // if ($reciept->move(public_path('orders-receipts'), $fileName)) {
            //     $recieptPath = 'orders-receipts/' . $fileName;
            //     $input['receipt'] = $recieptPath;
            // }
            //dd("ok");
            $recieptPath = uploadS3File($request , "orders-receipts" ,"receipt","order-recipts",$filename = null);
            //dd($recieptPath);
            $input['receipt'] = $recieptPath;
        }
        $orderModal->sub_total=$input['subTotal'];
        $orderModal->shipment_amount=$input['shippingAmount'];
        $orderModal->grand_total=$input['grandTotal'];
        $orderModal->billing_email=$input['billingEmail'];
        $orderModal->billing_phone_number=$input['billingPhoneNumber'];
        $orderModal->billing_user_name=$input['billingUsername'];
        $orderModal->billing_address=$input['billingAddress'];
        $orderModal->billing_city_id=$input['billingCityId'];
        $orderModal->billing_country_id=$input['billingCountryId'];
        $orderModal->is_billing_address_is_shipping=$input['isBillingAddressIsShipping'];
        $orderModal->shipping_email=$input['shippingEmail'];
        $orderModal->shipping_phone_number=$input['shippingPhoneNumber'];
        $orderModal->shipping_user_name=$input['shippingUsername'];
        $orderModal->shipping_address=$input['shippingAddress'];
        $orderModal->shipping_city_id=$input['shippingCityId'];
        $orderModal->shipping_country_id=$input['shippingCountryId'];
        $orderModal->payment_method_id=isset($input['paymentMethodID'])?$input['paymentMethodID'] :'';
        $orderModal->product_payment_method_id=isset($input['productPaymentMethodId']) ?$input['productPaymentMethodId'] : '';
        $orderModal->receipt =isset($input['receipt']) ? $input['receipt'] : '';
        $orderModal->user_id =$input['userId'];

        if ($orderModal->save()) {

            if (!empty($input['paymentDetails']) && isset($input['paymentDetails'])) {
                $modelRecieptDetails = new OrderPaymentDetail();
                $modelRecieptDetails->order_id = $orderModal->id;
                $modelRecieptDetails->payment_method_id = $input['paymentDetails']['paymentMethodId'];
                $modelRecieptDetails->product_payment_method_id = $input['paymentDetails']['productPaymentMethodId'];
                $modelRecieptDetails->payment_method_detail_id = $input['paymentDetails']['paymentMethodDetailId'];
                $modelRecieptDetails->payment_method_field_value = $input['paymentDetails']['paymentMethodFieldValue'];
                $modelRecieptDetails->save();
            }
            $cartItems = Cart::whereIn('id', $cartIds)->get();
            $items = [];
            $productName = '';
            $loop = 1;
            foreach ($cartItems as $key => $item) {
                $items[$key]['order_id'] = $orderModal->id;
                $items[$key]['user_id'] = $item->user_id;
                $items[$key]['product_id'] = $item->product_id;
                $items[$key]['quantity'] = $item->quantity;
                $items[$key]['unit_price'] = $item->unit_price;
                $items[$key]['total'] = $item->total;
                if ($loop == 1) {

                    $productName .= Product::where('id', $item->product_id)->value('name_english');
                } else {
                    $productName .= "," . Product::where('id', $item->product_id)->value('name_english');
                }
                $loop++;
            }

            $orderModal->orderItems()->createMany($items);
            Cart::whereIn('id', $cartIds)->delete();
        }

        $data=[];

        if ($userID) {
            $user=User::where('id',$userID)->first();
            if(!empty($user)){
                $userName = $user->user_name;
                $userEmail = $user->email;
                $details = [
                    'user_name' => "" . $userName . "",
                    'content' => "<p> You successfully Order Product  " . $productName . ".</p>",
                    'links' => "<a href='" . url('user/mustafai-store') . "'>Click Here</a>",
                ];
                try {
                    \Mail::to($userEmail)->send(new \App\Mail\CommonMail($details));
                } catch (Exception $e) {
                    //catch code
                }
            }
            $data['order_id'] = $orderModal->id;
            return response()->json(
            [
                'status'  => 1,
                'message' => "Order Placed.",
                'data' => $data
            ]);

        }
    }
    /**
     *remove cart api
    */
    public function removeCart()
    {
        $cartId = (int) $_GET['cartId'];
        $delete = Cart::destroy($cartId);
        // $response = [];
        if ($delete) {
            $status = 1;
            $message = 'Cart Removed';
        } else {
            $status = 0;
            $message = 'Cart Not Removed';
        }

        return response()->json(
            [
                'status'  => $status,
                'message' => $message,
            ]);
    }

}
