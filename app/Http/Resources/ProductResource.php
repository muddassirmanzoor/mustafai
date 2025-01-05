<?php
namespace App\Http\Resources;

use App\Models\Admin\EmployeeSection;
use App\Models\Posts\Post\Post;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Admin\ShipmentCharge;
class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        $ship_charges=ShipmentCharge::pluck('shipment_rate')->first();
        $lang = request()->lang;
        return [

            'id' => $this->id,
            'categoryId' => $this->category_id,
            'vendorId' => $this->vendor_id,
            'name'=> $this->{'name_'.$lang},
            'description'=> $this->{'description_'.$lang},
            'filePath'=> getS3File($this->file_name),
            'fileType'=>$this->file_type,
            'price'=>$this->price,
            'product_type'=>$this->product_type,
            'shipping_amount'=>$ship_charges,
            'featured'=>$this->featured,
            'productType'=>$this->product_type,
            'isShipmentChargesApply'=>$this->is_shipment_charges_apply,
            'new'=>$this->new,
            'images'=>$this->productImages,
        ];
    }

}
