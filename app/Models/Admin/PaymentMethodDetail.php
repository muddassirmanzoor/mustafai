<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_method_id',
        'method_fields',
    ];
    public function donationPaymentMethodDetails()
    {
        return $this->hasMany(DonationPaymentMethodDetails::class, 'payment_method_detail_id', 'id');
    }

    function paymentMethod()
    {
        return $this->belongsTo(paymentMethod::class);
    }
}
