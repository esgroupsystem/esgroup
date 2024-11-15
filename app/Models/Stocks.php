<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'product_id',
        'product_inQTY',
        'product_outQTY',
        'transaction_date',
        'remarks',
    ];
}
