<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreStop;
use App\Stop;

ini_set('memory_limit', '-1');

class StopController extends Controller
{
    public function index(Request $request)
    {
        $machine_id = $request->only("machine_id");
        $operator_id = $request->only("operator_id");

        $items = array();
        if($machine_id) { $items["machine_id"] = $machine_id; }

        if($operator_id) { $items["operator_id"] = $operator_id; }

        $date_base = Carbon::now()->toDateString();

        $stops = DB::table('stops')
                ->where($items)
                ->whereDate('stop_datetime_end','>=',Carbon::now()->subDays(2)->toDateString())
                ->get();

        return compact("stops");
    }

    public function store(StoreStop $request)
    {
        if($request->type == 1) {
            $dateTimeStart = date('Y-m-d H:i:s', strtotime($request->stop_datetime_start));
            $dateTimeEnd = date('Y-m-d H:i:s', strtotime($request->stop_datetime_end));

            $values = array( 
                'machine_id' => $request->machine_id,
                'operator_id' => $request->operator_id,
                'product_id' => $request->product_id,
                'color_id' => $request->color_id,
                'code_id' => $request->code_id,
                'conversion_id' => $request->conversion_id,
                'quantity' => $request->quantity,
                'meters' => $request->meters,
                'comment' => $request->comment,
                'stop_datetime_start' => $dateTimeStart,
                'stop_datetime_end' => $dateTimeEnd
            );
            
            $stop = Stop::create($values);

            if($stop) {
                $success = true;
            } else {
                $success = false;
            }

            $id = $stop->id;
            //return $id;
            return compact('success','id');
        } else {
            $operatorId = $request->operator_id;
            $stop = Stop::createForOperator($request, $operatorId);

            if($stop) {
                $success = true;
            } else {
                $success = false;
            }

            return compact('success');
        }
    }
    /*public function store(Request $request)
    {
        //$operatorId = Auth::guard('api')->id();
        //$operatorId = $request->operator_id;
        //$stop = Stop::createForOperator($request, $operatorId);

        $dateTimeStart = date('Y-m-d H:i:s', strtotime($request->stop_datetime_start));
        $dateTimeEnd = date('Y-m-d H:i:s', strtotime($request->stop_datetime_end));

        $values = array( 
            'machine_id' => $request->machine_id,
            'operator_id' => $request->operator_id,
            'product_id' => $request->product_id,
            'color_id' => $request->color_id,
            'code_id' => $request->code_id,
            'conversion_id' => $request->conversion_id,
            'quantity' => $request->quantity,
            'meters' => $request->meters,
            'comment' => $request->comment,
            'stop_datetime_start' => $dateTimeStart,
            'stop_datetime_end' => $dateTimeEnd
        );
        
        $stop = Stop::create($values);

        if($stop) {
            $success = true;
        } else {
            $success = false;
        }

        $id = $stop->id;
        //return $id;
        return compact('success','id');
        //return compact("date");
    }*/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Stop::whereId($id)->with([
            'code' => function($query) {
                $query->select('id', 'code', 'description','type');
            }, 
            'machine' => function($query) {
                $query->select('id', 'machine_name', 'process_id');
            }, 
            'product' => function($query) {
                $query->select('id', 'product_name');
            }, 
            'color' => function($query) {
                $query->select('id', 'name','hex_code');
            },
            'operator' => function($query) {
                $query->select('id', 'name', 'username', 'process_id');
            }
        ])->get([
            "id",
            "code_id",
            "machine_id",
            "product_id",
            "color_id",
            "operator_id",
            "meters",
            "comment",
            "stop_datetime_start",
            "stop_datetime_end"
        ]);
    }

    public function last_datetime_stop(Request $request) 
    {
        $operatorId = Auth::guard('api')->id();

        $data = $request->only([
            'machine_id'
        ]);

        $stop = Stop::where('machine_id', $data['machine_id'])
                    //->where('operator_id', $operatorId)
                    ->latest('id')
                    ->first();
        
        if(isset($stop->stop_datetime_end_12)){
            $success = $stop->stop_datetime_end_12;
        }else {
            $user = Auth::guard('api')->user();

            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $known = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();

            setlocale(LC_ALL, 'es_ES');
            Carbon::setlocale('es');
            $date = new Carbon($known->login_at);
            $success = $date->formatLocalized('%d %B, %Y').' '.$date->format('g:i:s a');
        }

        return compact('success');
    }

    public function update(Request $request, $id)
    {
        $stop = Stop::findOrFail($id);
        $stop->update($request->all());

        return $stop;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stop = Stop::findOrFail($id);
        $stop->delete();

        if($stop) {
            $success = true;
        } else {
            $success = false;
        }

        return compact('success');
    }

    public function stops_report()
    {
        //$stops = Stop::all();
        $stops = DB::table('stops')
                ->leftJoin('codes','stops.code_id','=','codes.id')
                ->leftJoin('machines','stops.machine_id','=','machines.id')
                ->leftJoin('users','stops.operator_id','=','users.id')
                ->leftJoin('products','stops.product_id','=','products.id')
                ->leftJoin('colors','stops.color_id','=','colors.id')
                ->leftJoin('processes','machines.process_id','=','processes.id')
                ->leftJoin('conversions','stops.conversion_id','=','conversions.id')
                ->select(
                    'stops.id',
                    'stops.machine_id',
                    'stops.operator_id',
                    'stops.quantity',
                    'stops.meters',
                    'stops.comment',
                    'stops.stop_datetime_start',
                    'stops.stop_datetime_end',
                    //DB::raw('YEAR(stops.stop_datetime_end) as year'),
                    //DB::raw('MONTH(stops.stop_datetime_end) as month'),
                    //DB::raw('WEEK(stops.stop_datetime_end) as week'),
                    //DB::raw('DATE_FORMAT(stops.stop_datetime_end, "%d/%m/%Y") as date'),
                    //DB::raw('HOUR(stops.stop_datetime_end) as hour'),
                    DB::raw('CASE WHEN HOUR(stops.stop_datetime_end) >= 7 AND HOUR(stops.stop_datetime_end) <= 17 THEN "D" ELSE "N" END as schedule'),
                    'processes.description as process',
                    'machines.machine_name',
                    'machines.warehouse',
                    'users.name as operator_name',
                    'products.product_name',
                    'colors.name as color_name',
                    'codes.code as stop_code',
                    'codes.description as code_description',
                    'codes.type as code_type',
                    'conversions.description as conversion_description'
                )
                //->whereRaw('DATE(stops.stop_datetime_end) BETWEEN (CURRENT_DATE() - INTERVAL 3 MONTH) AND CURRENT_DATE()')
                ->whereRaw("DATE_FORMAT(stops.stop_datetime_end, '%Y%m') BETWEEN DATE_FORMAT(CURRENT_DATE() - INTERVAL 3 MONTH, '%Y%m') AND DATE_FORMAT(CURRENT_DATE(), '%Y%m')")
                ->orderBy('stops.stop_datetime_end','desc')
                ->get();
        //$stops->load(['machine','operator','product','color','conversion']);
        //$stops->makeHidden(['machine','operator','code','product','color','conversion']);
        return $stops;
    }
}
