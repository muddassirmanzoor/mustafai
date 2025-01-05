<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $guarded = [];
    // public function bank()
    // {
    //     return $this->hasOne(Bank::class);
    // }
    public function bank() {
        return $this->hasOne(Bank::class,'id','bank_id') ;
    }
    public function mustafai_account()
    {
        return $this->hasOne(UserSubscription::class, 'id','mustafai_account_id');
    }
}
