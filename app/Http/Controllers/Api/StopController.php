<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use App\Http\Requests\StoreStop;
use App\Stop;

class StopController extends Controller
{
    public function index(Request $request)
    {
        $machine_id = $request->only("machine_id");
        $order = $request->only("order");

        if($order == 1) {
            $sort = "ASC";
        } else {
            if($order == 2) {
                $sort = "DESC";
            }
        }

        $user = Auth::guard('api')->user();
        return $user->asOperatorStops()->where('machine_id',$machine_id)->with([
            'code' => function($query) {
                $query->select('id', 'code', 'description','type');
            }, 
            'machine' => function($query) {
                $query->select('id', 'machine_name');
            }, 
            'product' => function($query) {
                $query->select('id', 'product_name');
            }, 
            'color' => function($query) {
                $query->select('id', 'name','hex_code');
            },
            'operator' => function($query) {
                $query->select('id', 'name', 'username');
            }
        ])->orderBy('id', $sort)->get([
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

    public function store(StoreStop $request)
    {
        $operatorId = Auth::guard('api')->id();
        $stop = Stop::createForOperator($request, $operatorId);

        if($stop) {
            $success = true;
        } else {
            $success = false;
        }

        return compact('success');
    }

    public function last_datetime_stop(Request $request) {
        $operatorId = Auth::guard('api')->id();

        $data = $request->only([
            'machine_id'
        ]);

        $stop = Stop::where('machine_id', $data['machine_id'])
                    ->where('operator_id', $operatorId)
                    ->latest('id')
                    ->first();
        
        $success = $stop->stop_datetime_end_12;

        return compact('success');
    }
}
