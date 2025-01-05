<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DonationPaymentHistory extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $count =  $this->libraries()->count();
        // dd($this->id);
        /** RECEIPT IMAGE***/

        $receipt_image = "";
        $path  = getS3File($this->receipt);

        if (\Storage::disk('s3')->exists($this->receipt)) {
            $receipt_image =  $path;
        } else {

            $receipt_image =  getS3File("images/product-placeholder-image.png");
        }


        return [
            'paymentId'         => $this->id,
            'donationtitle'     => isset($this->Donation)?$this->Donation->title_english: '',
            'date'              =>  \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
            'amount'            => sprintf("%.2f", $this->amount),
            'receipt'          => $receipt_image,
            'status'            => $this->status,
        ];
    }
}
