<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'machine_name', 'machine_code', 'warehouse', 'device_id'
    ];

    protected $hidden = [
        'created_at','updated_at','device_id','machine_code'
    ];

    public function device()
    {
        return $this->hasOne(Device::class);
    }
}
