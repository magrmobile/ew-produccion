<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'machine_name', 'machine_code', 'warehouse', 'device_id'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
