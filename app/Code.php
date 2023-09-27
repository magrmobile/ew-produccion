<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
