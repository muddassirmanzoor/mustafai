<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationPaymentMethod extends Model
{
    use HasFactory;

    public function donationPaymentMethodDetails()
    {
        return $this->hasMany(DonationPaymentMethodDetails::class, 'donation_pay_method_id', 'id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethod');
    }
}
