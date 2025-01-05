<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanPaymentMethod extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function paymentMethodDetail()
    {
        return $this->hasOne(PaymentMethodDetail::class, 'id', 'payment_method_detail_id');
    }
}
