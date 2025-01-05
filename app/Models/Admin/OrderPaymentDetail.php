<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPaymentDetail extends Model
{
    use HasFactory;

    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethod');
    }
    public function paymentMethodDetail()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethodDetail');
    }
}
