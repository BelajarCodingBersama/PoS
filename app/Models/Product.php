<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'price',
        'amount', 'product_type_id', 'file_id'
    ];

    /** Relationship */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
