<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AddJob;

class ApplyForJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'job_title',
        'name',
        'phone',
        'email',
        'status',
        'message',
        'cv_upload',
    ];

    public function job()
    {
        return $this->belongsTo(AddJob::class, 'job_id', 'id');
    }
}
