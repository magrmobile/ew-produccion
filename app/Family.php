<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'family_name'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    // 1 $family->products N
    public function products() 
    {
        return $this->hasMany(Product::class);
    }
}