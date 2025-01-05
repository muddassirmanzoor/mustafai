<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationList extends JsonResource
{

    public function toArray($request)
    {
        // dd($this->id);
        return [
            'id' => $this->id,
            'donation_category_id' => $this->donation_category_id,
            'title' => $this->{'title_'.$request->lang},
            'description' => $this->{'description_'.$request->lang},
            'totalAmountRequired'   => $this->price,
            'paidAmount' => $this->donationAmmount(),
            'progress' => $this->percentDonations(),
        ];
    }
}
