<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryCode extends Model
{
    use HasFactory;
    protected $table = 'country_codes';

    public function countryCode()
    {
        return $this->hasOne(User::class, 'country_code_id');
    }
}
