<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'sourceGarage',
        'receiverGarage',
        'product_category',
        'product_code',
        'product_name',
        'product_serial',
        'product_brand',
        'product_unit',
        'product_details',
        'product_outqty',
        'date_transfer',
        'status',
    ];
}
