<?php

namespace App\Http\Resources;

use App\Models\Admin\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Admin\ShipmentCharge;


class CartListingResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = request()->lang;
        $product_detail=Product::find($this->product_id);
        $ship_charges=ShipmentCharge::pluck('shipment_rate')->first();
        return [
            'cartId'=>$this->id,
            'userId' => $this->user_id,
            'productId' => $this->product_id,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unit_price,
            'totalPrice' => $this->total,
            'id' => $product_detail->id,
            'categoryId' => $product_detail->category_id,
            'vendorId' => $product_detail->vendor_id,
            'name'=> $product_detail->{'name_'.$lang},
            'description'=> $product_detail->{'description_'.$lang},
            'filePath'=> getS3File($product_detail->file_name),
            'fileType'=>$product_detail->file_type,
            'price'=>$product_detail->price,
            'product_type'=>$product_detail->product_type,
            'shipping_amount'=>$ship_charges,
            'featured'=>$product_detail->featured,
            'productType'=>$product_detail->product_type,
            'isShipmentChargesApply'=>$product_detail->is_shipment_charges_apply,
            'new'=>$product_detail->new,
            'images'=>$product_detail->productImages,

        ];
    }
}
