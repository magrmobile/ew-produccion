<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use App\Http\Requests\StoreStop;
use App\Stop;

class StopController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();
        return $user->asOperatorStops()->with([
            'code' => function($query) {
                $query->select('id', 'description','type');
            }, 
            'machine' => function($query) {
                $query->select('id', 'machine_name');
            }, 
            'product' => function($query) {
                $query->select('id', 'product_name');
            }, 
            'color' => function($query) {
                $query->select('id', 'name','hex_code');
            }
        ])->get([
            "id",
            "code_id",
            "machine_id",
            "product_id",
            "color_id",
            "meters",
            "comment",
            "stop_date_start",
            "stop_time_start",
            "stop_date_end",
            "stop_time_end"
        ]);
    }

    public function store(StoreStop $request)
    {
        $patientId = Auth::guard('api')->id();
        $success = Stop::createForOperator($request, $patientId);
        
        return compact('success');
    }
}
