<?php

namespace App\Http\Resources;

use App\Models\Admin\PaymentMethodDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationPaymentMethodDetailsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'donation_pay_method_id' => $this->donation_pay_method_id,
            'payment_method_field_title'   => $this->paymentMethodDetail->method_fields_english,
            'payment_method_field_value' => $this->payment_method_field_value,
        ];
    }
}
