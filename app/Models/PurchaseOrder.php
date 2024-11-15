<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_no',
        'product_name',
        'product_id',
        'supplier_id',
        'garage_id',
        'requestor_id',
        'isapproved',
        'total_amount',
        'grand_total',
    ];
}
