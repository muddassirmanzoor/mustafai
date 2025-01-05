<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class GroupChat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function readedMessagesByUsers()
    {
        return $this->hasMany(ReadedGroupChat::class, 'sms_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id','from_id');
    }
}
