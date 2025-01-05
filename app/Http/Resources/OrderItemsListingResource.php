<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsListingResource extends JsonResource
{
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'productId' => $this->product_id,
            'product' => $this->product->name_english,
            'productImage' => (isset($this->product->productImages[0])) ? getS3File($this->product->productImages[0]->file_name) : getS3File('images/products-images/default.png'),
            'quantity' => $this->quantity,
            'unitPrice' => $this->unit_price,
            'totalPrice' => $this->total,
            'lang' => "english",
        ];
    }
}
