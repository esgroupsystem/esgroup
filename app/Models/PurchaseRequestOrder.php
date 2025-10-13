<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'garage_name',
        'product_category',
        'product_name',
        'product_code',
        'product_serial',
        'product_brand',
        'product_unit',
        'product_details',
        'product_qty',
        'product_qty_sold',
        'request_date',
        'request_status',
    ];
}
