<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'stop_time_start',
        'stop_date_end',
        'stop_time_end'
    ];

    protected $hidden = [
        'code_id', 'machine_id', 'product_id', 'color_id', 'stop_time_start', 'stop_time_end'
    ];

    protected $appends = [
        'stop_time_start_12', 'stop_time_end_12'
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

    // accesor
    // $stop->stop_time_start_12
    public function getStopTimeStart12Attribute()
    {
        return(new Carbon($this->stop_time_start))
            ->format('g:i A');
    }

    // $stop->stop_time_end_12
    public function getStopTimeEnd12Attribute()
    {
        return(new Carbon($this->stop_time_end))
            ->format('g:i A');
    }
}
