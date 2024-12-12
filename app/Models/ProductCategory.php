<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'category_name',
        'category_status',
        'category_creator',
    ];

    public function products()
    {
        return $this->hasMany(Products::class, 'product_category'); // Assuming 'category_id' is the foreign key
    }
}
