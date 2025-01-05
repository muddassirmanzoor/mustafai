<?php

namespace Database\Seeders;

use App\Models\Admin\DonationReceipt;
use App\Models\Admin\DonationRecieptDetail;
use Illuminate\Database\Seeder;

class DonationRecieptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //__________DonationPaymentMethodDetails contains Fields Of DonationPaymentmethod(Admin Account fields Details)____//
        DonationReceipt::create([
            'donation_id' => 1,
            'payment_method_id' => 3,
            'donation_payment_method_id' => 3,
            'email' => 'Admin 1',
            'name' => "uaer@user.com",
            'phone' => "03030457106",
            'address' => "user address",
            'amount' => "20.00",
            'receipt' => "images/reciepts/reciept1660308555.png",
            'status' => 0
        ]);
        DonationRecieptDetail::create([
            'donation_receipt_id' => 1,
            'donation_payment_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 3,
            'payment_method_field_value' => 'user account Title',
        ]);
        DonationRecieptDetail::create([
            'donation_receipt_id' => 1,
            'donation_payment_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 4,
            'payment_method_field_value' => 'user account number',
        ]);
        DonationRecieptDetail::create([
            'donation_receipt_id' => 1,
            'donation_payment_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 5,
            'payment_method_field_value' => 'user bank name',
        ]);
        DonationRecieptDetail::create([
            'donation_receipt_id' => 1,
            'donation_payment_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 6,
            'payment_method_field_value' => 'user branch id',
        ]);
        DonationRecieptDetail::create([
            'donation_receipt_id' => 1,
            'donation_payment_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 7,
            'payment_method_field_value' => 'user iban number',
        ]);
    }
}
