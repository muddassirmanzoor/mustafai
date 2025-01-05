<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabinetUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class,'cabinet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    // public function role()
    // {
    //     return $this->hasOne(Role::class, 'id', 'role_id');
    // }
}
