<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    function groupUsers()
    {
        return $this->hasMany(GroupUser::class);
    }

    function groupSms()
    {
        return $this->hasMany(GroupChat::class);
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    // image accessor
    public function getIconAttribute($icon)
    {
        return is_null($icon) || $icon == '' ? 'images/group-icon.webp' : $icon;
    }
}
