<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartsOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'partsout_id',
        'product_category',
        'product_code',
        'product_name',
        'product_serial',
        'product_brand',
        'product_unit',
        'product_details',
        'product_outqty',
        'date_partsout',
        'bus_number',
        'kilometers',
        'status',
    ];
}
