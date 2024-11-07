<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Joborder extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_name',
        'job_type',
        'job_datestart',
        'job_time_start',
        'job_time_end',
        'job_sitNumber',
        'job_remarks',
        'job_status',
        'job_assign_person',
        'job_date_filled',
        'job_creator',
    ];
    
    public function jobFiles()
    {
        return $this->hasMany(JobFiles::class, 'job_id');
    }
}
