<?php

namespace App\Models\Admin;

use App\Models\Store\Cart;
use App\Models\Admin\District;
use App\Models\Admin\City;
use App\Models\Store\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'id','city_id');
    }

    // image accessor
    public function getImageAttribute($image)
    {
        return is_null($image) || $image == '' ? 'images/avatar.png' : $image;
    }
}
