<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_english',
        'donation_category_id',
        'title_urdu',
        'title_arabic',
        'description_english',
        'description_urdu',
        'description_arabic',
        'price',
        'file',
        'is_featured',
        'donation_type',
        'status',

    ];

    function donationAmmount()
    {
        return DonationReceipt::where('status', 1)->where('donation_id', $this->id)->sum('amount');
    }

    public function donationReciepts()
    {
        return $this->hasMany(DonationReceipt::class, 'donation_id', 'id');
    }
    function percentDonations()
    {
        $recievedDonation = DonationReceipt::where('status', 1)->where('donation_id', $this->id)->sum('amount');
        $requiredDonation = $this->price;
        $percent = ($recievedDonation / $requiredDonation) * 100;
        return $percent = number_format((float)$percent, 2, '.', '');
    }
}
