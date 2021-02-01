<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name', 'metal_type', 'stock'
    ];

    // N $stop->machine 1
    public function family() 
    {
        return $this->belongsTo(Family::class);
    }
}
