<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JobFiles extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_id',
        'file_name',
        'file_remarks',
        'file_notes',
        'file_path',
    ];
    public function jobOrder()
    {
        return $this->belongsTo(Joborder::class, 'job_id');
    }

}
