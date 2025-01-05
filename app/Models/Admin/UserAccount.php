<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function user_account()
    {
        return $this->hasOne(UserSubscription::class, 'id','user_account_id');
    }
}
