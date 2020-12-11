<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = [
        'machine_id',
        'operator_id',
        'product_id',
        'color_id',
        'code_id',
        'meters',
        'comment',
        'stop_date_start',
        'stop_time_start'
    ];

    // N $stop->machine 1
    public function machine() 
    {
        return $this->belongsTo(Machine::class);
    }

    // N $stop->operator 1
    public function operator() 
    {
        return $this->belongsTo(User::class);
    }

    // N $stop->product 1
    public function product() 
    {
        return $this->belongsTo(Product::class);
    }

    // N $stop->color 1
    public function color() 
    {
        return $this->belongsTo(Color::class);
    }

    // N $stop->code 1
    public function code() 
    {
        return $this->belongsTo(Code::class);
    }
}
