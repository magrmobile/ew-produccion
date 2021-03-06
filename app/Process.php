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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
