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
        'stop_datetime_start_12', 
        'stop_datetime_end_12', 
        'year',
        'month',
        'week',
        'date',
        'schedule',
        'process',
        'dayname_start', 
        'dayname_end', 
        'duration', 
        'machine_name',
        'warehouse',
        'operator_name',
        'product_name',
        'color_name',
        'stop_start',
        'stop_end',
        'stop_code',
        'code_description',
        'stop_type',
        'conversion_description'
    ];

    protected $dates = ['stop_datetime_start', 'stop_datetime_end', 'created_at', 'updated_at'];

    // N $stop->machine 1
    public function machine() 
    {
        return $this->belongsTo(Machine::class);
    }

    // $stop->machine_name
    public function getMachineNameAttribute()
    {
        $machineName = null;
        if($this->machine) {
            $machineName = $this->machine->machine_name;
        }
        return $machineName;
    }

    // N $stop->operator 1
    public function operator() 
    {
        return $this->belongsTo(User::class);
    }

    // $stop->operator_name
    public function getOperatorNameAttribute()
    {
        $operatorName = null;
        if($this->operator) {
            $operatorName = $this->operator->name;
        }
        return $operatorName;
    }

    // N $stop->product 1
    public function product() 
    {
        return $this->belongsTo(Product::class);
    }

    // $stop->product_name
    public function getProductNameAttribute()
    {
        $productName = null;
        if($this->product) {
            $productName = $this->product->product_name;
        }
        return $productName;
    }

    // 1 $stop->family 1 : through product
    public function family()
    {
        return $this->hasOneThrough(Family::class, Product::class);
    }

    // N $stop->color 1
    public function color() 
    {
        return $this->belongsTo(Color::class);
    }

    // $stop->color_name
    public function getColorNameAttribute()
    {
        $colorName = null;
        if($this->color) {
            $colorName = $this->color->name;
        }
        return $colorName;
    }

    // N $stop->code 1
    public function code() 
    {
        return $this->belongsTo(Code::class);
    }

    // $stop->stop_code
    public function getStopCodeAttribute()
    {
        return $this->code->code;
    } 

    // $stop->code_description
    public function getCodeDescriptionAttribute()
    {
        $codeName = null;
        if($this->code) {
            $codeName = $this->code->description;
        }
        return $codeName;
    }

    // N $stop->conversion 1
    public function conversion() 
    {
        return $this->belongsTo(Conversion::class);
    }

    // $stop->conversion_description
    public function getConversionDescriptionAttribute()
    {
        $conversionName = null;
        if($this->conversion) {
            $conversionName = $this->conversion->description;
        }
        return $conversionName;
    }

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

    // $stop->year
    public function getYearAttribute()
    {
        return $this->stop_datetime_end->format('Y');
    }

    // $stop->month
    public function getMonthAttribute()
    {
        return $this->stop_datetime_end->format('m');
    }

    // $stop->week
    public function getWeekAttribute()
    {
        return $this->stop_datetime_end->format('w');
    }

    // $stop->date
    public function getDateAttribute()
    {
        return $this->stop_datetime_end->format('j/m/Y');
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

    // $stop->schedule
    public function getScheduleAttribute()
    {
        $date = new Carbon($this->stop_datetime_start);
        $hour = $date->format('G');

        if($hour >= 7 && $hour <= 17) {
            return "D";
        } else {
            return "N";
        }
    }

    // $stop->process
    public function getProcessAttribute()
    {
        return $this->machine->process->description;
    }

    // $stop->warehouse
    public function getWarehouseAttribute()
    {
        return $this->machine->warehouse;
    }

    // $stop->stop_start
    public function getStopStartAttribute()
    {
        return $this->stop_datetime_start->format('H:i:s');
    }

    // $stop->stop_end
    public function getStopEndAttribute()
    {
        return $this->stop_datetime_end->format('H:i:s');
    }

    // $stop->duration
    public function getDurationAttribute()
    {
        $date1 = new Carbon($this->stop_datetime_start);
        $date2 = new Carbon($this->stop_datetime_end);

        $interval = $date1->diff($date2);

        return $interval->format('%H:%I:%s');
    }

    // $stop->stop_type
    public function getStopTypeAttribute()
    {
        return $this->code->type;
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
