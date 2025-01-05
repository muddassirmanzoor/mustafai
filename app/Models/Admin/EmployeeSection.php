<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'section_id',
        'name_english',
        'name_urdu',
        'name_arabic',
        'designation_english',
        'designation_urdu',
        'designation_arabic',
        'short_description_english',
        'short_description_urdu',
        'short_description_arabic',
        'content_english',
        'content_urdu',
        'content_arabic',
        'image',
        'status',
    ];
    public function section()
    {
        return $this->belongsTo('App\Models\Admin\Section');
    }

    public function libraryAlbums($select =null,$id)
    {
        if(!empty($select)){
            return $this->belongsToMany(LibraryAlbum::class, 'employee_section_library_albums')->select($select)->where('employee_section_library_albums.type_id',$id);
        }else{
            return $this->belongsToMany(LibraryAlbum::class, 'employee_section_library_albums')->where('employee_section_library_albums.type_id',$id);
        }
    }

    public function getImageAttribute($image)
    {
        return is_null($image) || $image == '' ? 'images/dummy-images/dummy.png' : $image;
    }
}
