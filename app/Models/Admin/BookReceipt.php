<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReceipt extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bookReceiptLeafs()
    {
        return $this->hasMany(BookReceiptLeaf::class, 'book_receipt_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(Admin::class,'issued_to','id');
    }
}
