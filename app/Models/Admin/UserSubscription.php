<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function mustafai_account()
    {
        return $this->belongsTo(BankAccount::class, 'mustafai_account_id');
    }
    public function user_account()
    {
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }
}
