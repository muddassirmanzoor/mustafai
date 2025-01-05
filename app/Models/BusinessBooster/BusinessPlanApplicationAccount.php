<?php

namespace App\Models\BusinessBooster;

use App\Models\Admin\BusinesPlan;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\PaymentMethodDetail;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanApplicationAccount extends Model
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
