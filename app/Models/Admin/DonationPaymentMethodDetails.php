<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationPaymentMethodDetails extends Model
{
    use HasFactory;

    public function donationPaymentMethod()
    {
        return $this->belongsTo('App\Models\Admin\DonationPaymentMethod');
    }
    public function paymentMethodDetail()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethodDetail');
    }
    // public function paymentMethod()
    // {
    //     return $this->belongsTo('App\Models\Admin\PaymentMethod');
    // }
}
