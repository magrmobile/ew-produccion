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
        'conversion_id',
        'quantity',
        'meters',
        'comment',
        'stop_datetime_start',
        'stop_datetime_end'
    ];

    protected $hidden = [
        'code_id', 'machine_id', 'product_id', 'color_id', 'conversion_id', 'created_at', 'updated_at'
    ];

    protected $appends = [
        'stop_datetime_start_12', 'stop_datetime_end_12', 'dayname_start', 'dayname_end', 'duration', 'schedule'
    ];

    protected $dates = ['stop_datetime_start', 'stop_datetime_end', 'created_at', 'updated_at'];

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

    public function family()
    {
        return $this->hasOneThrough(Family::class, Product::class);
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

    // N $stop->conversion 1
    public function conversion() 
    {
        return $this->belongsTo(Conversion::class);
    }

    // accesor
    // $stop->stop_datetime_start_12
    public function getStopDateTimeStart12Attribute()
    {
        setlocale(LC_ALL, 'es_ES');
        Carbon::setlocale('es');
        $date = new Carbon($this->stop_datetime_start);
        return $date->formatLocalized('%d %B, %Y').' '.$date->format('g:i:s a');
    }

    // $stop->stop_datetime_end_12
    public function getStopDateTimeEnd12Attribute()
    {
        setlocale(LC_ALL, 'es_ES');
        Carbon::setlocale('es');
        $date = new Carbon($this->stop_datetime_end);
        return $date->formatLocalized('%d %B, %Y').' '.$date->format('g:i:s a');
    }

    // $stop->dayname_start
    public function getDayNameStartAttribute()
    {
        setlocale(LC_ALL, 'es_ES.UTF-8');
        Carbon::setlocale('es');
        $date = new Carbon($this->stop_datetime_start);
        return $date->formatLocalized('%A');
    }

    // $stop->dayname_end
    public function getDayNameEndAttribute()
    {
        setlocale(LC_ALL, 'es_ES.UTF-8');
        Carbon::setlocale('es');
        $date = new Carbon($this->stop_datetime_end);
        return $date->formatLocalized('%A');
    }

    // $stop->duration
    public function getDurationAttribute()
    {
        $date1 = new Carbon($this->stop_datetime_start);
        $date2 = new Carbon($this->stop_datetime_end);

        $interval = $date1->diff($date2);

        return $interval->format('%H:%I:%s');
    }

    // $stop->schedule
    public function getScheduleAttribute()
    {
        $date = new Carbon($this->stop_datetime_end);
        $hour = $date->format('g');

        if($hour >= 6 && $hour <= 18) {
            return "D";
        } else {
            return "N";
        }
    }

    static public function createForOperator(Request $request, $operatorId) 
    {

        $data = $request->only([
            'machine_id',
            'product_id',
            'color_id',
            'code_id',
            'conversion_id',
            'quantity',
            'meters',
            'comment',
        ]);

        $machine_id = $request->machine_id;

        $data['operator_id'] = $operatorId;

        $date = Carbon::now();

        $lastStop = Stop::where('machine_id', $machine_id)
                        //->where('operator_id', $operatorId)
                        ->latest('id')
                        ->first();

        if($lastStop == null) {
            $data['stop_datetime_start'] = auth()->user()->lastLoginAt();
        } else {
            /*if($lastStop->stop_date_start == null) {
                Stop::where('id',$lastStop->id)->update(['stop_datetime_start' => $date]);
            } else {*/
                $data['stop_datetime_start'] = $lastStop->stop_datetime_end;
            //}
        }

        $data['stop_datetime_end'] = $date;

        return self::create($data);
    }
}
