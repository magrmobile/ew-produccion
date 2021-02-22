<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'family_id', 'process_id', 'product_name', 'metal_type', 'stock'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    // N $stop->machine 1
    public function family() 
    {
        return $this->belongsTo(Family::class);
    }

    public function process() 
    {
        return $this->belongsTo(Process::class);
    }
}
