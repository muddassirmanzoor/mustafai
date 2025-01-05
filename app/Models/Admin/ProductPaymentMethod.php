<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPaymentMethod extends Model
{
    use HasFactory;

    public function productPaymentMethodDetails()
    {
        return $this->hasMany(ProductPaymentMethodDetails::class, 'product_pay_method_id', 'id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethod');
    }
}
