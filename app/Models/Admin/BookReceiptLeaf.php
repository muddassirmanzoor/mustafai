<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReceiptLeaf extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'book_receipt_leafs';
}
