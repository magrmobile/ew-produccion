<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name', 'hex_code'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
