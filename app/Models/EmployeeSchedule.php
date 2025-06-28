<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    use HasFactory;

    protected $table = 'employee_schedules';
    protected $fillable = [
        'employee_id',
        'work_date',
        'start_time',
        'end_time',
    ];

    public $timestamps = true;      
}
