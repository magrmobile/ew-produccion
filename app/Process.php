<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [
        'description'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
