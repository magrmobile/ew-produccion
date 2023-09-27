<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'machine_name', 'process_id', 'warehouse', 'device_id', 'supervisor_id', 'location'
    ];

    protected $hidden = [
        'created_at','updated_at', 'device_id'
    ];

    protected $appends = [
        'device_name', 'process_name'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function getDeviceNameAttribute()
    {
        $deviceName = null;
        if($this->device) {
            $deviceName = $this->device->device_name;
        }
        return $deviceName;
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function getProcessNameAttribute()
    {
        $processName = null;
        if($this->process) {
            $processName = $this->process->description;
        }
        return $processName;
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('speed'); // Incluye el parametro de velocidad
    }
}
