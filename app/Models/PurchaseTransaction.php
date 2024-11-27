<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    use HasFactory;
    protected $fillable = [

        'purchase_id',
        'request_id',
        'total_amount',
        'payment_terms',
    ];
}
