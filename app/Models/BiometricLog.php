<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricLog extends Model
{
    use HasFactory;
    protected $fillable = [
    'employee_id',
    'employee_name',
    'log_time',
    'status',
];

    protected $casts = [
        'log_time' => 'datetime',
    ];
}
