<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'serial_number', 'device_name', 'description'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }
}
