<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'machine_name', 'process_id', 'warehouse', 'device_id'
    ];

    protected $hidden = [
        'created_at','updated_at','device_id'
    ];

    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }
}
