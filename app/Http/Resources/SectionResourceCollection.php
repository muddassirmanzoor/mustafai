<?php
namespace App\Http\Resources;

use App\Models\Admin\EmployeeSection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class SectionResourceCollection extends JsonResource
{
  
    public function toArray($request)
    {
        
        return [
         
            'id' => $this->id,
            'name' => $this->name,
            'members' => EmployeeSectionResource::collection($this->employee_sections->where('status',1)),
          
        ];
    }

}