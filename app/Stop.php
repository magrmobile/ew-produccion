<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    static public function createForOperator(Request $request, $operatorId) 
    {

        $data = $request->only([
            'machine_id',
            'product_id',
            'color_id',
            'code_id',
            'meters',
            'comment',
        ]);

        $data['operator_id'] = $operatorId;

        $date = Carbon::now();

        $current_date = $date->format('Y-m-d');
        $current_time = $date->format('H:i:s');

        $data['stop_date_end'] = $current_date;
        $data['stop_time_end'] = $current_time;

        $lastLogin = Carbon::createFromFormat('Y-m-d H:i:s',auth()->user()->lastLoginAt());

        $lastLoginDate = $lastLogin->format('Y-m-d');
        $lastLoginTime = $lastLogin->format('H:i:s');

        $lastStop = Stop::where('operator_id', $operatorId)
                        ->latest('id')
                        ->first();

        if($lastStop == null) {
            $data['stop_date_start'] = $lastLoginDate;
            $data['stop_time_start'] = $lastLoginTime;
        } else {
            if($lastStop->stop_date_start == null) {
                Stop::where('id',$lastStop->id)->update(['stop_date_start' => $current_date, 'stop_time_start' => $current_time]);
            } else {
                $data['stop_date_start'] = $lastStop->stop_date_end;
                $data['stop_time_start'] = $lastStop->stop_time_end;
            }
        }

        return self::create($data);
    }
}
