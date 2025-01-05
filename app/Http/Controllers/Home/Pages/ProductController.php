<?php

namespace App\Http\Controllers\Home\Pages;

use App;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Category;
use App\Models\Admin\City;
use App\Models\Admin\Country;
use App\Models\Admin\OrderPaymentDetail;
use App\Models\Admin\Product;
use App\Models\Admin\ShipmentCharge;
use App\Models\Store\Order;
use Auth;
use Illuminate\Http\Request;
use Darryldecode\Cart\Cart;
use Illuminate\Contracts\Session\Session;
use App\Models\Admin\Setting;

class ProductController extends Controller
{
    /**
     *get mustafai store products with filters
    */
    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $catId = $_GET['category'];
            $sortId = $_GET['sortId'];
            $product_type = (isset($_GET['product_type'])) ? $_GET['product_type'] : '';
            $saerch = $_GET['searchFilter'];
            $order = explode(' ', $sortId);

            $productIds = $_GET['productIds'];
            $where = '1';
            if ($catId != '') {
                $where .= ' AND category_id=' . $catId;
            }
            if ($product_type != '') {
                $where .= ' AND product_type=' . $product_type;
            }
            if ($saerch != '') {
                $where .= ' AND name_' . App::getLocale() . ' LIKE ' . "'%" . $saerch . "%'";
            }
            if ($productIds[0] != '') {
                $productIds = implode(',', $productIds);
                $where .= ' AND id NOT IN (' . $productIds . ')';
            }

            $query = getQuery(App::getLocale(), ['name', 'description']);
            $query[] = 'id';
            $query[] = 'price';
            $query[] = 'product_type';
            $products = Product::select($query)->whereRaw($where)->where('status', 1)->limit(9)->orderBy($order[0], $order[1])->get();
            $data = [];
            $data['products'] = $products;
            $html = (string) View('home.partial.products-partial', $data);

            $response = [];
            $response['html'] = $html;
            $response['loadMore'] = count($products);
            echo json_encode($response);
            exit();
        }

        $data = [];
        $query = getQuery(App::getLocale(), ['name']);
        $query[] = 'id';
        $data['categories'] = Category::select($query)->where('status', 1)->get();
        return view('home.home-pages.our-products-pages.all-products', $data);
    }

    /**
     *Add to cart Products
    */
    public function addToCartSession(Request $request)
    {
        $product = Product::find($request->product_id);
        \Cart::add(array(
            array(
                'id' => $request->product_id,
                'name' => $product->name_english,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'attributes' => array('subTotal' => $product->price * $request->quantity),
                'associatedModel' => $product
            ),
        ));
        $cartCollection = \Cart::getContent();
        $data = $cartCollection->count();
        return ['status' => 200, 'message' => 'successfully created', 'data' => $data];
    }

    /**
     *Place order for mustafai store on homepage
    */
    public function orderNowHome(Request $request)
    {

        $input = $request->all();
        // dd($input);
        $userID = (Auth::check()) ? Auth::user()->id : null;
        $cartIds = $input['cartIds'];
        $cartIds = explode(',', $cartIds);
        // dd($cartIds);

        $orderModal = new Order();

        $path = 'orders-receipts';
        if ($request->order_reciept) {
            // $reciept = 'reciept' . time() . '.' . $request->order_reciept->extension();
            // if ($request->order_reciept->move(public_path($path), $reciept)) {
            //     $path = $path . "/" . $reciept;
            // }
            $reciept = 'reciept' . time() . '.' . $request->order_reciept->extension();

            $path=$request->order_reciept->storeAs(
                'orders-receipts',
                $reciept,
                's3'
            );
        }

        // $input = $request->only(['email', 'donation_payment_method_id', 'payment_method_id', 'donation_id', 'user_id', 'name', 'phone', 'address', 'amount', 'receipt', 'status']);
        $orderModal->fill($request->except(['cartIds', 'order_reciept', 'method_details', 'payment_method_mustafai_id']));
        $orderModal->fill(['user_id' => $userID]);
        $orderModal->fill(['receipt' => $path]);


        if ($orderModal->save()) {

            foreach ($request->method_details as $key => $val) {
                if (!empty($val)) {
                    $modelRecieptDetails = new OrderPaymentDetail();
                    $modelRecieptDetails->order_id = $orderModal->id;
                    $modelRecieptDetails->payment_method_id = $input['payment_method_id'];
                    $modelRecieptDetails->product_payment_method_id = $input['product_payment_method_id'];
                    $modelRecieptDetails->payment_method_detail_id = $key;
                    $modelRecieptDetails->payment_method_field_value = $val;
                    $modelRecieptDetails->save();
                }
            }
            $items = [];
            $dataa = [];

            $productName = '';
            $loop = 1;
            $cartItems = \Cart::getContent();
            foreach ($cartItems as $key => $item) {
                if (in_array($item->id, $cartIds)) {
                    // dd("ok");
                    $items[$key]['order_id'] = $orderModal->id;
                    $items[$key]['user_id'] = $userID;
                    $items[$key]['product_id'] = $item->id;
                    $items[$key]['quantity'] = $item->quantity;
                    $items[$key]['unit_price'] = $item->price;
                    $items[$key]['total'] = $item->attributes->subTotal;
                    if ($loop == 1) {

                        $productName .= Product::where('id', $item->product_id)->value('name_english');
                    } else {
                        $productName .= "," . Product::where('id', $item->product_id)->value('name_english');
                    }
                    $loop++;
                    // $dataa['cartId'] = $item->id;
                    $this->removeCartFun($item->id);
                    // \Cart::remove($item->id);
                }
            }

            $orderModal->orderItems()->createMany($items);

            $response = [];
            $response['status'] = 1;
            $response['message'] = 'Order Placed.';

            if ($userID) {
                $userName = Auth::user()->user_name;
                $userEmail = Auth::user()->email;
                $details = [
                    'subject' => "Order Delivered Successfully",
                    'user_name' => "" . $userName . "",
                    'content' => "<p>Your order has been delivered successfully.Thanks for purchasing the product from Mustafai Store.</p>",
                    'links' => "<a href='" . url('user/mustafai-store') . "'>Click Here </a>to buy more products",
                ];
                try {
                    \Mail::to($userEmail)->send(new \App\Mail\CommonMail($details));
                } catch (Exception $e) {
                    //catch code
                }
            } else {
                $userName = $input['billing_user_name'];
                $details = [
                    'subject' => "Order Delivered Successfully",
                    'user_name' => "" . $userName . "",
                    'content' => "<p>Your order has been delivered successfully.Thanks for purchasing the product from Mustafai Store.</p>",
                    'links' => "<a href='" . url('user/mustafai-store') . "'>Click Here </a>to buy more products",
                ];
                try {
                    \Mail::to($input['billing_email'])->send(new \App\Mail\CommonMail($details));
                } catch (Exception $e) {
                    //catch code
                }
            }
            $details = [
                'subject' => "Order Received",
                'user_name' => "Super Admin",
                'content' => "<p> A user named " . $userName . "  placed an order via Mustafai Store .</p>",
                'links' => "<a href='" . url('admin/orders') . "'>Click here</a> to log in and view order details.",
            ];
            // $email = Admin::find(1)->value('email');
            $email = settingValue('emailForNotification');
            saveEmail($email, $details);
            $cartCollection = \Cart::getContent();
            $response['ccount'] = $cartCollection->count();

            echo json_encode($response);
            exit();
        }
    }

    /**
     *Get cart items
    */
    public function getCartHome()
    {
        $cartItems = \Cart::getContent();

        // dd($cartItems);
        $data['cartItems'] = $cartItems;
        $name_query = getQuery(App::getLocale(), ['name']);
        $name_query[] = 'id';
        $data['cities'] = City::select($name_query)->where('status', 1)->get();
        $data['countries'] = Country::select($name_query)->where('status', 1)->get();
        $data['shipment_rate'] = ShipmentCharge::first('shipment_rate');
        $html = (string) View('home.partial.home-cart-item-partial', $data);
        return $html;
    }

    /**
     *Remove cart items
    */
    public function removeCart(Request $request)
    {
        \Cart::remove($request->cartId);
        $cartCollection = \Cart::getContent();
        $data['cart_count'] = $cartCollection->count();
        // \Cart::remove($request->id);
        return ['status' => 'deleted', 'data' => $data];
    }

    /**
     *Remove cart item helper function
    */
    public function removeCartFun($id)
    {
        return \Cart::remove($id);
        // \Cart::remove($request->id);

    }
}
