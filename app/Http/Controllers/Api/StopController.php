<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

use App\Http\Requests\StoreStop;
use App\Stop;

ini_set('memory_limit', '-1');

class StopController extends Controller
{
    public function index(Request $request)
    {
        $machine_id = $request->only("machine_id");

        $date_base = Carbon::now()->toDateString();

        //$user = Auth::guard('api')->user();
        //return $user->asOperatorStops()->where('machine_id',$machine_id)->with([
        if($machine_id) {
            return Stop::where('machine_id',$machine_id)
                ->whereDate('stop_datetime_end','>=',Carbon::now()->subDays(2)->toDateString())
                ->with([
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
                'conversion' => function($query) {
                    $query->select('id', 'description', 'factor', 'type');
                },
                'operator' => function($query) {
                    $query->select('id', 'name', 'username', 'process_id');
                }
            ])->orderBy('id', 'ASC')->get([
                "id",
                "code_id",
                "machine_id",
                "product_id",
                "color_id",
                "operator_id",
                "conversion_id",
                "quantity",
                "meters",
                "comment",
                "stop_datetime_start",
                "stop_datetime_end"
            ]);
        } else {
            return Stop::with([
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
            'conversion' => function($query) {
                $query->select('id', 'description', 'factor', 'type');
            },
            'operator' => function($query) {
                $query->select('id', 'name', 'username', 'process_id');
            }
            ])->orderBy('id', 'ASC')->get([
                "id",
                "code_id",
                "machine_id",
                "product_id",
                "color_id",
                "operator_id",
                "conversion_id",
                "quantity",
                "meters",
                "comment",
                "stop_datetime_start",
                "stop_datetime_end"
            ]);
        }
    }

    public function store(StoreStop $request)
    {
        //$operatorId = Auth::guard('api')->id();
        $operatorId = $request->operator_id;
        $stop = Stop::createForOperator($request, $operatorId);

        if($stop) {
            $success = true;
        } else {
            $success = false;
        }

        return compact('success');
    }

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
        $stops = Stop::all();
        //$stops->load(['machine','operator','product','color','conversion']);
        $stops->makeHidden(['machine','operator','code','product','color','conversion']);
        return $stops;
    }
}
