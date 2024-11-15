<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders_details';
    
    protected $fillable =[
        'transaction_id',	
        'po_no_id'	,
        'product_id',
        'store_id',	
        'order_qty',
    ];
}
