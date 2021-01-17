<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'serial_number', 'mac_address', 'device_name', 'description'
    ];

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }
}
