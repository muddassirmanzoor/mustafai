<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    function getCartCounter()
    {
        $userID = Auth::user()->id;
        return self::where('user_id',$userID)->count();
    }
    function getCartCounterapi($user_id)
    {
        $userID = $user_id;
        return self::where('user_id',$userID)->count();
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Admin\Product');
    }
}
