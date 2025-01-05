<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\UnionCouncil;

class PermanentAddress extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function union_council_permanent_relation()
    {
        return $this->hasOne(UnionCouncil::class, 'id', 'union_council_permanent');
    }

}
