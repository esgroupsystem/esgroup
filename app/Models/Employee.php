<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'employee_id',
        'company',
        'department',
        'designation',
        'garage',
        'date_hired',
        'end_date',
        'status',
    ];
}
