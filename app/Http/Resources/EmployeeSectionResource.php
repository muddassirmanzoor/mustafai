<?php
namespace App\Http\Resources;

use App\Models\Admin\EmployeeSection;
use App\Models\Admin\EmployeeSectionLibraryAlbum;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class EmployeeSectionResource extends JsonResource
{
    public function toArray($request)
    {
        $library_count=EmployeeSectionLibraryAlbum::where('employee_section_id',$this->id)->count();
        $lang =request()->lang;
        return [
            'id' => $this->id,
            'name' => $this->{'name_'.$lang},
            'image' => !empty($this->image)? getS3File($this->image): '',
            'designation' => $this->{'designation_'.$lang},
            'shortDescription' => $this->{'short_description_'.$lang},
            'description' => $this->{'content_'.$lang},
            'library_count' => $library_count,
        ];
    }

    public function with($request){
        return [
            'status' => 1,
            'message' => 'success',
        ];
    }
}
