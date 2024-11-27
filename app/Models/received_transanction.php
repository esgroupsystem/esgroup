<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class received_transanction extends Model
{
    use HasFactory;

    protected $fillable = [

        'po_id',
        'product_code',
        'qty_received',
        'status',
    ];
}
