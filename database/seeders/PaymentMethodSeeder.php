<?php

namespace Database\Seeders;

use App\Models\Admin\DonationPaymentMethod;
use App\Models\Admin\DonationPaymentMethodDetails;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\PaymentMethodDetail;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //___________FOr Payment Method Add By Deafult Some Payment Methods Of Jazzcash easypaisa,Bank Transfer________//
        PaymentMethod::create([
            'id' => 1,
            'method_name_english' => 'JazzCash',
            'method_name_urdu' => 'جاز کیش',
            'method_name_arabic' => 'جاز كاش',

        ]);
        PaymentMethodDetail::create([
            'payment_method_id' => 1,
            'method_fields_english' => 'JazzCash Account',
            'method_fields_urdu' => 'جاز کیش اکاؤنٹ',
            'method_fields_arabic' => 'حساب جاز كاش',
        ]);
        //________donation Payment Methods Depend Upon Admmin Account In Which User Send His Donations___//
        DonationPaymentMethod::create([
            'payment_method_id' => 1,
            'account_title' => 'Admin First Jazz Cash Account',
            'status' => 1,
        ]);
        //__________DonationPaymentMethodDetails contains Fields Of DonationPaymentmethod(Admin Account fields Details)____//
        DonationPaymentMethodDetails::create([
            'donation_pay_method_id' => 1,
            'payment_method_id' => 1,
            'payment_method_detail_id' => 1,
            'payment_method_field_value' => '03030457106',
        ]);

        //________For Easy Paisa Account_____________//
        PaymentMethod::create([
            'id' => 2,
            'method_name_english' => 'EasyPaisa',
            'method_name_urdu' => 'ایزی پیسہ',
            'method_name_arabic' => 'ایزی پیسہ',

        ]);
        PaymentMethodDetail::create([
            'payment_method_id' => 2,
            'method_fields_english' => 'EasyPaisa Account',
            'method_fields_urdu' => 'ایزی پیسہ اکاؤنٹ',
            'method_fields_arabic' => 'ایزی پیسہ اکاؤنٹ',
        ]);
        //________donation Payment Methods Depend Upon Admmin Account In Which User Send His Donations___//
        DonationPaymentMethod::create([
            'payment_method_id' => 2,
            'account_title' => 'Admin First EasyPaisa Account',
            'status' => 1,
        ]);
        //__________DonationPaymentMethodDetails contains Fields Of DonationPaymentmethod(Admin Account fields Details)____//
        DonationPaymentMethodDetails::create([
            'donation_pay_method_id' => 2,
            'payment_method_id' => 2,
            'payment_method_detail_id' => 2,
            'payment_method_field_value' => '03030457106',
        ]);
        //__ For Bank Account___________________//
        PaymentMethod::create([
            'id' => 3,
            'method_name_english' => 'BankTransfer',
            'method_name_urdu' => 'بینک ٹرانسفر',
            'method_name_arabic' => 'حوالة بنكية',

        ]);

        PaymentMethodDetail::create([
            'payment_method_id' => 3,
            'method_fields_english' => 'Account Title',
            'method_fields_urdu' => 'اکاؤنٹ کا عنوان',
            'method_fields_arabic' => 'عنوان الحساب',
        ]);
        PaymentMethodDetail::create([
            'payment_method_id' => 3,
            'method_fields_english' => 'Account Number',
            'method_fields_urdu' => 'اکاؤنٹ نمبر',
            'method_fields_arabic' => 'رقم حساب',
        ]);
        PaymentMethodDetail::create([
            'payment_method_id' => 3,
            'method_fields_english' => 'Bank Name',
            'method_fields_urdu' => 'بینک کا نام',
            'method_fields_arabic' => 'اسم البنك',
        ]);
        PaymentMethodDetail::create([
            'payment_method_id' => 3,
            'method_fields_english' => 'Branch ID / Number / Code',
            'method_fields_urdu' => 'برانچ آئی ڈی / نمبر / کوڈ',
            'method_fields_arabic' => 'معرف الفرع / الرقم / الرمز',
        ]);
        PaymentMethodDetail::create([
            'payment_method_id' => 3,
            'method_fields_english' => 'IBAN Number',
            'method_fields_urdu' => 'IBAN نمبر',
            'method_fields_arabic' => 'رقم الآيبان',
        ]);
        //________donation Payment Methods Depend Upon Admmin Account In Which User Send His Donations___//
        DonationPaymentMethod::create([
            'payment_method_id' => 3,
            'account_title' => 'Admin First Bank Account',
            'status' => 1,
        ]);
        //__________DonationPaymentMethodDetails contains Fields Of DonationPaymentmethod(Admin Account fields Details)____//
        DonationPaymentMethodDetails::create([
            'donation_pay_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 3,
            'payment_method_field_value' => 'Admin 1',
        ]);
        DonationPaymentMethodDetails::create([
            'donation_pay_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 4,
            'payment_method_field_value' => 'Account Number 1',
        ]);
        DonationPaymentMethodDetails::create([
            'donation_pay_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 5,
            'payment_method_field_value' => 'Admin Bank Name 1',
        ]);
        DonationPaymentMethodDetails::create([
            'donation_pay_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 6,
            'payment_method_field_value' => 'Admin Branch Code 1',
        ]);
        DonationPaymentMethodDetails::create([
            'donation_pay_method_id' => 3,
            'payment_method_id' => 3,
            'payment_method_detail_id' => 7,
            'payment_method_field_value' => 'Admin IBAN Number 1',
        ]);
    }
}
