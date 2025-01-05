<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ReadedGroupChat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function groupSms()
    {
        return $this->belongsTo(GroupChat::class);
    }
}
