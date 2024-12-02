<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'garage',
        'name',
        'body_number',
        'plate_bumber',
    ];
}
