<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Device;

class DeviceController extends Controller
{
    public function machines(Request $request)
    {
        $serial_number = $request->only('serialNumber');

        return $dev_machines = Device::where('serial_number', $serial_number)->with('machines')->get();
    }
}
