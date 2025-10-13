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
        'garage_name',
        'supplier_name',
        'product_code',
        'product_complete_name',
        'product_qty',
        'grand_total',
        'total_amount',
        'payment_terms',
        'status_receiving',
        'date_received',
        'remarks',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id', 'purchase_id');
    }
}
