<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'family_id', 'process_id', 'product_name', 'metal_type', 'stock'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'family_id', 'process_id'
    ];

    protected $appends = [
        'family_name',
        'process_name'
    ];
    
    protected $dates = ['created_at', 'updated_at'];

    // N $stop->machine 1
    public function family() 
    {
        return $this->belongsTo(Family::class);
    }

    public function getFamilyNameAttribute()
    {
        $familyName = null;
        if($this->family) {
            $familyName = $this->family->family_name;
        }
        return $familyName;
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

    public function machines()
    {
        return $this->belongsToMany(Machine::class)
            ->withPivot('speed'); // Incluye el parametro de velocidad
    }
}
