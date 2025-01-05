<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $table = 'sections';
    protected $fillable = [
        'name_english',
        'name_urdu',
        'name_arabic',
        'content_english',
        'content_urdu',
        'content_arabic',
        'status',

    ];
    public function employee_sections($status=null)
    {
       if(!empty($status)){

           return $this->hasMany(EmployeeSection::class, 'section_id', 'id')->where('status',$status);
       }else{
           return $this->hasMany(EmployeeSection::class, 'section_id', 'id');

       }
    }
}
