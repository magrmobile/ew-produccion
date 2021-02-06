<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name', 'metal_type', 'stock'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'family_id'
    ];

    // N $stop->machine 1
    public function family() 
    {
        return $this->belongsTo(Family::class);
    }
}
