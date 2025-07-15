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
        'bus_id',
        'start_time',
        'end_time',
        'time-in',
        'time-out',
        'remit',
        'diesel'
    ];

    public $timestamps = true;      
}
