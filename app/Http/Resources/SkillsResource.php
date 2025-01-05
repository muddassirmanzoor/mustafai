<?php

namespace App\Http\Resources;

use App\Models\Admin\Occupation;
use Illuminate\Http\Resources\Json\JsonResource;
use Mockery\Exception;
use Illuminate\Support\Facades\Auth;
use DB;

class SkillsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'skills' => ['skills_english' => !empty($this->skills_english) ? explode(',', $this->skills_english) :[], 'skills_urdu' =>!empty($this->skills_urdu) ?  explode(',', $this->skills_urdu):[], 'skills_arabic' =>!empty($this->skills_arabic) ?  explode(',', $this->skills_arabic):[]],
        ];
    }

}
