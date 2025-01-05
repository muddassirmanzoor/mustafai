<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPaymentMethodDetails extends Model
{
    use HasFactory;

    public function donationPaymentMethod()
    {
        return $this->belongsTo('App\Models\Admin\ProductPaymentMethod');
    }
    public function paymentMethodDetail()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethodDetail');
    }
}
