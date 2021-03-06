<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'description',
        'type'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
