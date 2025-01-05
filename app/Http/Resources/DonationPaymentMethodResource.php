<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationPaymentMethodResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'payment_method_id' => $this->payment_method_id,
            'account_title' => $this->account_title,
            'status' => $this->status,
            'mustafai_payment_methods_for_accepting_donations_details' => DonationPaymentMethodDetailsResource::collection($this->donationPaymentMethodDetails)
        ];
    }
}
