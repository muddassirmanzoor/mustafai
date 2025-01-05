<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationReceipt extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Donation()
    {
        return $this->belongsTo('App\Models\Admin\Donation');
    }
    public function donationPaymentMethod()
    {
        return $this->belongsTo('App\Models\Admin\DonationPaymentMethod');
    }
    public function donationRecieptDetails()
    {
        return $this->hasMany(DonationRecieptDetail::class, 'donation_receipt_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
