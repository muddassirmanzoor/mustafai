<?php

namespace App\Models\Store;

use App\Models\Admin\OrderPaymentDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\Admin\Product');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    // relation of payments____//
    public function productPaymentMethod()
    {
        return $this->belongsTo('App\Models\Admin\ProductPaymentMethod');
    }
    public function orderPaymentDetails()
    {
        return $this->hasMany(OrderPaymentDetail::class, 'order_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**CUSTOM FUNCTION  FOR BANK DETAILS**/
    /** ADMIN**/
    function AdminPaymentDetails($admin_bank_details_arr)
    {
        if (!empty($this->productPaymentMethod->productPaymentMethodDetails)) {


            foreach ($this->productPaymentMethod->productPaymentMethodDetails as $val) {
                if ($val->payment_method_detail_id == 1 || $val->payment_method_detail_id == 2) {
                    $admin_bank_details_arr["accountNumber"] = $val->payment_method_field_value;
                }
                if ($val->payment_method_detail_id == 3) {
                    $admin_bank_details_arr["accountTitle"] = $val->payment_method_field_value;
                }
                if ($val->payment_method_detail_id == 4) {
                    $admin_bank_details_arr["accountNumber"] = $val->payment_method_field_value;
                }
                if ($val->payment_method_detail_id == 5)
                    $admin_bank_details_arr["bankName"] = $val->payment_method_field_value;
            }
            return  $admin_bank_details_arr;
        }
    }
    /** USER**/
    function UserPaymentDetails($user_bank_details)
    {

        foreach ($this->orderPaymentDetails as $val) {

            if ($val->payment_method_detail_id == 1 || $val->payment_method_detail_id == 2) {
                $user_bank_details["accountNumber"] = $val->payment_method_field_value;
            }
            if ($val->payment_method_detail_id == 4) {
                $user_bank_details["accountNumber"] = $val->payment_method_field_value;
            }
            if ($val->payment_method_detail_id == 5)
                $user_bank_details["bankName"] = $val->payment_method_field_value;
        }
        return  $user_bank_details;
    }
}
