<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_serial',
        'product_name',
        'product_category',
        'product_brand',
        'product_unit',
        'product_parts_details',
        'product_instock',
        'product_outstock',
        'product_status',
        'product_creator',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category');
    }

    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand');
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit');
    }

    public function productTotalStocks()
    {
        return $this->hasMany(product_total_stocks::class, 'product_id');
    }

    public function productStockBalintawak()
    {
        return $this->hasMany(ProductStockBalintawak::class, 'product_id');
    }

    public function productStockVgc()
    {
        return $this->hasMany(ProductStockVgc::class, 'product_id');
    }
}

