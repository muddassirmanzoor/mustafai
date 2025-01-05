<?php

namespace App\Http\Controllers\User;

use App;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Category;
use App\Models\Admin\City;
use App\Models\Admin\Country;
use App\Models\Admin\OrderPaymentDetail;
use App\Models\Admin\Product;
use App\Models\Admin\ShipmentCharge;
use App\Models\Store\Cart;
use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use App\Models\User;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Admin\Setting;

class ProductController extends Controller
{
    /**
     *Listing the products
    */
    public function getProducts(Request $request)
    {
        if (!have_permission('View-Mustafai-Store')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        if ($request->ajax()) {
            $catId = (isset($_GET['category'])) ? $_GET['category']:'';
            $sortId = (isset($_GET['sortId'])) ? $_GET['sortId'] : '';
            $product_type = (isset($_GET['product_type'])) ? $_GET['product_type'] : '';
            $saerch = (isset($_GET['searchFilter'])) ? $_GET['searchFilter'] : '';
            $order = explode(' ', $sortId);

            $productIds = (isset($_GET['productIds'])) ? $_GET['productIds'] : [];
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
            if ($saerch != '') {
                $where .= ' OR description_' . App::getLocale() . ' LIKE ' . "'%" . $saerch . "%'";
            }
            if (count($productIds) && $productIds[0] != "") {
                $productIds = implode(',', $productIds);
                $where .= ' AND id NOT IN (' . $productIds . ')';
            }

            $query = getQuery(App::getLocale(), ['name', 'description']);
            $query[] = 'id';
            $query[] = 'price';
            $query[] = 'product_type';
            if(count($order) && $order[0] != "")
            {
                $products = Product::select($query)->whereRaw($where)->where('status', 1)->limit(9)->orderBy($order[0], $order[1])->get();
            }
            else
            {
                $products = Product::select($query)->whereRaw($where)->where('status', 1)->limit(9)->get();
            }

            $data = [];
            $data['products'] = $products;
            $html = (string) View('user.partials.products-partial', $data);

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
        return view('user.store', $data);
    }

    /**
     *Adding products to cart
    */
    public function addCart(Request $request)
    {
        $modal = new Cart();
        $response = [];
        $input = $request->all();
        $input['user_id'] = $userID = Auth::user()->id;

        if (!isset($input['product_id'])) {
            $response['status'] = 0;
            $response['ccount'] = $modal->getCartCounter();
            $response['message'] = __('app.something-went-wrong');
            echo json_encode($response);
            exit();
        }
        unset($input['type']);
        $product = Product::find($input['product_id']);
        $input['unit_price'] = $product->price;
        $input['total'] = ($product->price * $input['quantity']);
        $modal->fill($input);

        $exist = Cart::where(['user_id' => $userID, 'product_id' => $product->id])->first();

        if (!empty($exist)) {
            $quantity = $exist->quantity + $input['quantity'];
            $total = $quantity * $input['unit_price'];
            $cart = $exist->update(['quantity' => $quantity, 'unit_price' => $input['unit_price'], 'total' => $total]);
        } else {
            $cart = $modal->save();
        }
        if($request->type=='product_shop'){
           return  redirect('user/mustafai-store')->with('success', __('app.added-into-cart'));
        }
        if ($cart) {
            $response['status'] = 1;
            $response['ccount'] = $modal->getCartCounter();
            $response['message'] = __('app.added-into-cart');
        } else {
            $response['status'] = 0;
            $response['ccount'] = $modal->getCartCounter();
            $response['message'] = __('app.something-went-wrong');
        }

        echo json_encode($response);
    }

    /**
     *Authenticated user can get access to cart
    */
    public function getCart()
    {
        $userID = Auth::user()->id;

        $query[] = 'id';
        $query[] = 'price';

        $cartItems = Cart::select('id', 'product_id', 'unit_price', 'quantity', 'total')->where('user_id', $userID)->get()->each(function ($item) {
            // return ($item->product->is_shipment_charges_apply != 0) ? $item->is_chargeable = 'true' : 'false';
            if($item->product->product_type == 1){
                return $item->is_chargeable = 'false';
            }else{
                // dd("else");
                return ($item->product->is_shipment_charges_apply != 0) ? $item->is_chargeable = 'true' : 'false';
                exit;
            }

        });

        $data = [];
        $data['items'] = $cartItems;
        $name_query = getQuery(App::getLocale(), ['name']);
        $name_query[] = 'id';
        $data['cities'] = City::select($name_query)->where('status', 1)->get();
        $data['countries'] = Country::select($name_query)->where('status', 1)->get();
        $data['cart_count'] = Cart::select('id', 'product_id', 'unit_price', 'quantity', 'total')->where('user_id', $userID)->count();
        $data['total'] = Cart::select('id', 'product_id', 'unit_price', 'quantity', 'total')->where('user_id', $userID)->sum('total');
        $data['shipment_rate'] = ShipmentCharge::first('shipment_rate');
        $html = (string) View('user.partials.cart-items-partial', $data);

        $response = [];
        $response['html'] = $html;
        echo json_encode($response);
        exit();
    }

    /**
     *Placing Order through cart
    */
    public function orderNow(Request $request)
    {

        $input = $request->all();
        $userID = Auth::user()->id;
        $cartIds = $input['cartIds'];
        $cartIds = explode(',', $cartIds);
        $orderModal = new Order();

        $path = 'orders-receipts';
        if ($request->order_reciept) {
            $reciept = 'reciept' . time() . '.' . $request->order_reciept->extension();

            $path=$request->order_reciept->storeAs(
                'orders-receipts',
                $reciept,
                's3'
            );
            // if ($request->order_reciept->move(public_path($path), $reciept)) {
            //     $path = $path . "/" . $reciept;
            // }
        }

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

        $cart = new Cart();
        $response = [];
        $response['status'] = 1;
        $response['message'] = 'Order Placed.';

        if ($userID) {
            $userName = Auth::user()->user_name;
            $userEmail = Auth::user()->email;
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
        } else {
            $userName = 'Guest User';
        }
        $details = [
            'user_name' => "Super Admin",
            'content' => "<p> A user named " . $userName . "  placed an order via Mustafai Store .</p>",
            'links' => "<a href='" . url('admin/orders') . "'>Click here</a> to log in and view order details.",
        ];
        // $email = Admin::find(1)->value('email');
        $email = settingValue('emailForNotification');
        try {
            \Mail::to($email)->send(new \App\Mail\CommonMail($details));
        } catch (Exception $e) {
            //catch code
        }
        $response['ccount'] = $cart->getCartCounter();

        echo json_encode($response);
        exit();
    }

    /**
     *Displaying Product details
    */
    public function productDetails(Request $request)
    {
        if ($request->ajax()) {
            $product = Product::with('productImages')->find($request->id);
            $view = view('home.partial.product-detail', compact('product'))->render();
            return response()->json(['status' => 200, 'html' => $view]);
        }
    }

    /**
     *Creating order plus moving image to folder
    */
    public function orderProduct(Request $request)
    {
        if ($request->hasFile('receipt')) {
            $path5 =  uploadS3File($request , "orders-receipts" ,"receipt","receipt",$filename = null);
            $path  = $path5;
        }

        // create order
        $order = Order::create(['ip' => !auth()->check() ? $request->ip() : null, 'user_id' => auth()->check() ? auth()->user()->id : null, 'receipt' => $path]);

        OrderItem::create([
            'order_id' => $order->id,
            'ip' => !auth()->check() ? $request->ip() : null,
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
        ]);

        return response()->json(['status' => 200]);
    }
    
    /**
     *Removing Cart items
    */
    public function removeCart()
    {
        $cartId = (int) $_GET['cartId'];
        $delete = Cart::destroy($cartId);

        $response = [];
        if ($delete) {
            $response['status'] = 'deleted';
            $response['message'] = 'Cart Removed';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Cart Not Removed';
        }

        echo json_encode($response);
        exit();
    }

    /**
     *get List Of Order History
    */
    public function myOrders(Request $request)
    {
        if (!have_permission('View-My-Orders')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        $data = [];
        if ($request->ajax()) {
            $db_record = Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    $status = '<span class="badge badge-danger" style="background-color: red;">' . __("app.pending") . '</span>';
                } elseif ($row->status == 2) {
                    $status = '<span class="badge badge-warning" style="background-color: #ffc107;">' . __("app.shipped") . '</span>';
                } elseif ($row->status == 3) {
                    $status = '<span class="badge badge-success" style="background-color: green;">' . __("app.completed") . '</span>';
                } else {
                    $status = '<span class="badge badge-danger" style="background-color: red;">' . __("app.cancelled") . '</span>';
                }
                return $status;
            });
            $datatable = $datatable->editColumn('statusHidden', function ($row) {
                if ($row->status == 1) {
                    $status = __("app.pending");
                } elseif ($row->status == 2) {
                    $status = __("app.shipped");
                } elseif ($row->status == 3) {
                    $status = __("app.completed");
                } else {
                    $status = __("app.cancelled");
                }
                return $status;
            });
            $datatable = $datatable->editColumn('user_id', function ($row) {
                if ($row->user_id) {
                    $userData = User::where('id', $row->user_id)->first();
                    return $userData->user_name;
                } else {
                    return "Guest User";
                }
            });

            $datatable = $datatable->addColumn('action', function ($row) {

                $actions = '<span class="actions">';
                // if (have_right('View-Detail-Orders')) {
                $actions .= '<a class=" ml-2" href="javascript:void(0)" title="View Detail" onclick="showOrderDetails(' . $row->id . ')"><span class="btn btn-primary"><i class="far fa-eye"></i></span></a>';
                // }
                $actions .= '</span>';
                return $actions;
            });
            $datatable = $datatable->addColumn('date', function ($row) {
                $carbonObject = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
                $date=$carbonObject->toDateString();
                return $date;
            });
            $datatable = $datatable->addColumn('time', function ($row) {
                $carbonObject = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
                $time =$carbonObject->format('h:i A');
                return $time;
            });

            $datatable = $datatable->rawColumns(['status', 'action', 'user_id','statusHidden','date','time']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('user.my-orders-list', $data);
    }
}
