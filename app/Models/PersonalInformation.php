<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'philhealth',
        'sss',
        'tin_no',
        'nationality',
        'religion',
        'marital_status',
        'employment_of_spouse',
        'children',
    ];
}
