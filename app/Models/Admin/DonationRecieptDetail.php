<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationRecieptDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethod');
    }
    public function paymentMethodDetail()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethodDetail');
    }
}
