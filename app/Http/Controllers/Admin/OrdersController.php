<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * listing the Orders.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Orders')) {
            access_denied();
        }

        $data = [];
        if ($request->ajax()) {
            $order_status = (isset($_GET['order_status']) && $_GET['order_status']) ? $_GET['order_status'] : '';
            $from_date = (isset($_GET['from_date']) && $_GET['from_date']) ? $_GET['from_date'] : '';
            $to_date = (isset($_GET['to_date']) && $_GET['to_date']) ? $_GET['to_date'] : '';
            $db_record = Order::orderBy('created_at', 'DESC')->get();
            $db_record = $db_record->when($order_status, function ($q, $order_status) {
                return $q->where('status', $order_status);
            });
            $db_record = $db_record->when($from_date, function ($q, $from_date) {
                $startDate = date('Y-m-d', strtotime($from_date)) . ' 00:00:00';
                return $q->where('created_at', '>=', $startDate);
            });
            $db_record = $db_record->when($to_date, function ($q, $to_date) {
                $endDate = date('Y-m-d', strtotime($to_date)) . ' 23:59:00';
                // dd($endDate);
                return $q->where("created_at", '<=', $endDate);
                // return $q->whereRaw("(date(created_at) <='" . $endDate . "')");
            });

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '';

                if ($row->status == 1) {
                    $status = '<span class="badge badge-info">Pending</span>';
                } elseif ($row->status == 2) {
                    $status = '<span class="badge badge-warning">Shipped</span>';
                } elseif ($row->status == 3) {
                    $status = '<span class="badge badge-success">Completed</span>';
                } elseif ($row->status == 4) {
                    $status = '<span class="badge badge-secondary">Rejected</span>';
                } else {
                    $status = '<span class="badge badge-danger">Cancelled</span>';
                }

                return $status;
            });
            $datatable = $datatable->editColumn('statusColumn', function ($row) {
                $status = '';

                if ($row->status == 1) {
                    $status = 'Pending';
                } elseif ($row->status == 2) {
                    $status = 'Shipped';
                } elseif ($row->status == 3) {
                    $status = 'Completed';
                } elseif ($row->status == 4) {
                    $status = 'Rejected';
                } else {
                    $status = 'Cancelled';
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
                if ($row->status != 4) {

                    if (have_right('Ready-Orders') || have_right('Complete-Orders')) {
                        if ($row->status == 1 && have_right('Ready-Orders')) {
                            $actions .= '<a class="btn btn-warning  ml-2" href="javascript:void(0)" title="Ready Shipment" onclick="orderStatusChange(' . $row->id . ',2)"><i class="fa fa-truck" style="color:white;"></i></a>';
                        } elseif ($row->status == 2 && have_right('Complete-Orders')) {
                            $actions .= '<a class="btn btn-success  ml-2" href="javascript:void(0)" title="Complete Shipment" onclick="orderStatusChange(' . $row->id . ',3)"><i class="fa fa-check"></i></a>';
                        }
                    }
                    $orderItem_products = $row->orderItems()->pluck('product_id');
                    $virtual_product = Product::whereIn('id', $orderItem_products)->where('product_type', 1)->first();
                    if (!empty($virtual_product)) {
                        if (have_right('Share-Virtual-Product-Link')) {
                            $actions .= '<a class="btn btn-secondary ml-2" href="javascript:void(0)" title="Share Virtual Product Link" onclick="orderMailVirtual(' . $row->id . ')"><i class="fa fa-share" aria-hidden="true"></i></a>';
                        }
                    }

                    if (have_right('View-Detail-Orders')) {
                        $actions .= '<a class="btn btn-primary ml-2" href="javascript:void(0)" title="Show" onclick="showOrderDetails(' . $row->id . ')"><i class="far fa-eye"></i></a>';
                    }
                    if (have_right('Reject-Orders')) {
                        $actions .= '<a class="btn btn-secondary ml-2" href="javascript:void(0)" title="Reject" onclick="orderStatusChange(' . $row->id . ',4)"><i class="fa fa-times" aria-hidden="true"></i></a>';
                    }
                }

                if (have_right('Delete-Orders')) {
                    if ($row->status == 1) {
                        $actions .= '<a class="btn btn-danger ml-2" href="javascript:void(0)" title="Delete" onclick="orderStatusChange(' . $row->id . ',4)"><i class="far fa-trash-alt"></i></a>';
                    }
                }
                // if (have_right('delete-admin')) {
                //     $actions .= '<form method="POST" action="' . url("admin/orders/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                //     $actions .= '<input type="hidden" name="_method" value="DELETE">';
                //     $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                //     $actions .= '<button class="btn btn-danger" style="margin-left:02px;" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                //     $actions .= '<i class="far fa-trash-alt"></i>';
                //     $actions .= '</button>';
                //     $actions .= '</form>';
                // }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'action', 'user_id']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.orders.listing', $data);
    }

    /**
     * display the Order details.
    */
    public function orderDetails(Request $request)
    {
        $data = [];
        $order_id = $request->order_id;
        $data['OrderItemdata'] = OrderItem::where('order_id', $order_id)->get();
        $data['orderData'] = Order::with('orderPaymentDetails.paymentMethod', 'orderPaymentDetails.paymentMethodDetail', 'productPaymentMethod.productPaymentMethodDetails', 'productPaymentMethod.paymentMethod')
            ->where('orders.id', $order_id)
            ->first();
        $html = (string) view('admin.partial.order-details', $data);
        $response = [];
        $response['html'] = $html;
        echo json_encode($response);
        exit();
    }

    /**
     * Update Order Status.
    */
    public function updateOrderStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->change_number;
        $update_status = Order::where('id', $id)->update(['status' => $status]);
        if ($update_status) {
            //___This Is For Mail_____________//
            $orderData = Order::where('id', $request->id)->first();
            $orderItem_products = $orderData->orderItems()->pluck('product_id');
            $products = Product::whereIn('id', $orderItem_products)->where('product_type', 1)->get();
            if ($request->change_number != 4) {
                if (!empty($orderData->user_id)) {
                    $userEmail = User::find($orderData->user_id)->value('email');
                    $user_name = User::find($orderData->user_id)->value('user_name');
                } else {
                    $userEmail = $orderData->billing_email;
                    $user_name = $orderData->billing_email;
                }
                if ($request->change_number == 2) {
                    $order = " delivered";
                } elseif ($request->change_number == 3) {
                    $order = " completed";
                    if (count($products)) {
                        $product_file = '';
                        foreach ($products as $product) {
                            $product_file = $product_file . '<div><a href="' . asset($product->file_name) . '"><img src="' . getS3File($product->productImages[0]->file_name) . '" alt="Mustafai Item" class="img-fluid" style="width: 60px;height: 80px;object-fit: cover; padding-right: 30px;border: 1px solid #60b994;padding: 14px;margin-right: 13px;"></a><p style="max-width: 90px;">' . $product->name_english . '</p></div>';
                        }
                        $virtualDetails = [
                            'subject' => "Virtual Product(s)",
                            'user_name' => $user_name,
                            'content' => "<p>This email contains the links to digital products that you just purchased from the Mustafai Portal.</p>" . "<div style='display:flex;'>" . $product_file . "</div>",
                            'links' => "<br><a href='" . url('/user/mustafai-store') . "'>Click here</a> to visit Mustafai Portal and purchase more products.",
                        ];
                        // dd($userEmail, $virtualDetails);
                        // sendEmail($userEmail, $virtualDetails);
                        saveEmail($userEmail, $virtualDetails);
                    }
                }
                $details = [
                    'subject' => "Order " . $order . " Successfully",
                    'user_name' => $user_name,
                    'content' => "<p>Your order has been " . $order . " successfully.  .</p><p>Thanks for purchasing the product from Mustafai Store</p>",
                    'links' => "<a href='" . url('/user/mustafai-store') . "'>Click here</a> to buy more products",
                ];

                try {
                    \Mail::to($userEmail)->send(new \App\Mail\CommonMail($details));
                } catch (Exception $e) {
                    //catch code
                }
            } else {
                $details = [
                    'subject' => "Order Rejected",
                    'user_name' => (empty($orderData->user_id) ? $orderData->billing_email : User::find($orderData->user_id)->value('user_name')),
                    'content' => "<p>Unfortunately your order has been rejected for certain reasons.</p><p>Apologize for the inconvenience.</p>",
                    'links' => "<a href='" . url('/user/mustafai-store') . "'>Click here </a>to buy more products.",
                ];

                try {
                    \Mail::to(empty($orderData->user_id) ? $orderData->billing_email : User::find($orderData->user_id)->value('email'))->send(new \App\Mail\CommonMail($details));
                } catch (Exception $e) {
                    //catch code
                }
            }

            echo true;
            exit();
        }
    }

    /**
     * Virtual Link Email Order Status.
    */
    public function VirtualLinkEmail(Request $request)
    {
        $id = $request->id;
        //___This Is For Mail_____________//
        $orderData = Order::where('id', $request->id)->first();
        $orderItem_products = $orderData->orderItems()->pluck('product_id');
        $products = Product::whereIn('id', $orderItem_products)->where('product_type', 1)->get();
        if (!empty($orderData->user_id)) {
            $userEmail = User::find($orderData->user_id)->value('email');
            $user_name = User::find($orderData->user_id)->value('user_name');
        } else {
            $userEmail = $orderData->billing_email;
            $user_name = $orderData->billing_email;
        }
        $product_file = '';
        foreach ($products as $product) {
            $product_file = $product_file . '<div><a href="' . asset($product->file_name) . '"><img src="' . getS3File($product->productImages[0]->file_name) . '" alt="Mustafai Item" class="img-fluid" style="width: 60px;height: 80px;object-fit: cover; padding-right: 30px;border: 1px solid #60b994;padding: 14px;margin-right: 13px;"></a><p style="max-width: 90px;">' . $product->name_english . '</p></div>';
        }
        $details = [
            'subject' => "Virtual Product(s)",
            'user_name' => $user_name,
            'content' => "<p>This email contains the links to digital products that you just purchased from the Mustafai Portal.</p>" . "<div style='display:flex;'>" . $product_file . "</div>",
            'links' => "<br><a href='" . url('/user/mustafai-store') . "'>Click here</a> to visit Mustafai Portal and purchase more products.",
        ];
        // sendEmail($userEmail, $details);
        saveEmail($userEmail, $details);
        echo true;
        exit();
    }
}
