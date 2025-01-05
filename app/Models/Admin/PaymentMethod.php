<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'method_name',
        'status',
    ];

    public function donationPaymentMethods()
    {
        return $this->hasMany(DonationPaymentMethod::class, 'payment_method_id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentMethodDetail::class, 'payment_method_id');
    }

}
