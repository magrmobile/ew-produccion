<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $fillable = [
        'description', 'factor', 'type'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
