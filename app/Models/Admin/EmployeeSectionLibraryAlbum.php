<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSectionLibraryAlbum extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_id',
        'employee_section_id',
        'library_album_id',
    ];
}
