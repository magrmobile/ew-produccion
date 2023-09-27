<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Device;
use App\Machine;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    public function machines(Request $request)
    {
        $serial_number = $request->only('serialNumber');
        $device = Device::where('serial_number', $serial_number)->get();

        //$machines = [];

        if(count($device) > 0) {
            $device_id = $device[0]->id;
            /*$machines = Machine::where('device_id', $device_id)
                ->select('id','machine_name','process_id','warehouse')
                ->get();*/
            $machines = DB::table('machines')
            ->where('device_id', $device_id)
            ->select('id','machine_name','process_id','warehouse')
            ->get();
        }

        return compact("machines");
    }
}
