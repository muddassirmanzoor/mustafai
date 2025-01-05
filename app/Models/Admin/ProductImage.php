<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'file_type',
        'file_name',
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\Admin\Product');
    }
}
