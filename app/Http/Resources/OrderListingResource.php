<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListingResource extends JsonResource
{
    public function toArray($request)
    {
        $full_name=$this->user->{'user_name_'.request()->lang};
        if ($this->status == 1)
            $status = "pending";
        elseif ($this->status == 2)
            $status = "shipped";
        elseif ($this->status == 3)
            $status = "completed";
        else
            $status = "cancelled";


        /** FUNCTION :AdminPaymentDetails
         * PRESENT IN ORDER MODEL**/
        $admin_bank_details = array("accountTitle" => "", "accountNumber" => "", "bankName" => "");

        if (!empty($this->productPaymentMethod->productPaymentMethodDetails)) {

            $admin_bank_details = $this->AdminPaymentDetails($admin_bank_details);
        }

        /** FUNCTION :UserPaymentDetails
         * PRESENT IN ORDER MODEL**/
        $user_bank_details = array("accountTitle" => "", "accountNumber" => "", "bankName" => "");
        if (!empty($this->orderPaymentDetails)) {
            $user_bank_details = $this->UserPaymentDetails($user_bank_details);
        }


        return [

            'id' => $this->id,
            'orderStatus' =>  $status,
            'orderItems' => OrderItemsListingResource::collection($this->orderItems),
            'user' => [
                'username' => !empty($this->user->user_name) ?  $this->user->user_name : "",
                'userId' => $this->user->id,
                // 'fullName' => !empty($this->user->full_name) ? $this->user->full_name : "",
                'fullName' => availableField($full_name, $this->user->user_name_english, $this->user->user_name_urdu, $this->user->user_name_arabic),
            ],
            'userAccountDetail' => [
                'paymentMethod' => !empty($this->orderPaymentDetails[0]->paymentMethod) ? $this->orderPaymentDetails[0]->paymentMethod->method_name_english : "",
                'paymentMethodValue' => !empty($this->orderPaymentDetails[0]->payment_method_field_value) ? $this->orderPaymentDetails[0]->payment_method_field_value : "",
                'otherDetail' =>  (!empty($this->orderPaymentDetails)) ? $this->orderPaymentDetails : '',
            ],
            'adminAccountDetail' => [
                'paymentMethod' => !empty($this->productPaymentMethod) ? $this->productPaymentMethod->paymentMethod->method_name_english : "",
                'accountTitle' =>  (!empty($this->productPaymentMethod->account_title)) ? $this->productPaymentMethod->account_title : '',
                'otherDetail' => (!empty($this->productPaymentMethod->productPaymentMethodDetails)) ? $this->productPaymentMethod->productPaymentMethodDetails : ''
            ],
            'dateTime' =>(string) $this->created_at,
        ];
    }
}
