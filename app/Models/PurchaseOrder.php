<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [

        'request_id',
        'po_number',
        'garage_name',
        'product_code',
        'product_serial',
        'product_name',
        'product_category',
        'product_brand',
        'product_unit',
        'product_supplier',
        'product_details',
        'payment_terms',
        'remarks',
        'purchase_date',
        'status',
    ];
}
