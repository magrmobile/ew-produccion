<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $fillable = [
        'package', 'factor', 'type'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
