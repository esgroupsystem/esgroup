<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'request_id',
        'request_date',
        'status',
        ];
}
