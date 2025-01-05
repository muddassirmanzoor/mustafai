<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'payment_method_name' => $this->method_name_english,
             'payment_method_details'=> PaymentMethodDetailsResource::collection($this->paymentDetails),
            'mustafai_payment_methods_for_accepting_donations' => DonationPaymentMethodResource::collection($this->donationPaymentMethods)
        ];
    }
}
