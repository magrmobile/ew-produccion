<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Device;

use Alphametric\Validation\Rules\MacAddress;

use Exception;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = Device::with('machines')->latest()->paginate(5);
        return view('devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('devices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'serial_number' => 'required|min:8',
            //'mac_address' => [new MacAddress ],
            'device_name' => 'required',
        ];

        $this->validate($request, $rules);

        Device::create(
            $request->only('serial_number','mac_address','device_name','description')
        );

        $notification = 'El Dispositivo se ha registrado correctamente';
        return redirect('/devices')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Device $device)
    {
        $rules = [
            'serial_number' => 'required|min:8',
            //'mac_address' => ['min:3', new MacAddress ],
            'device_name' => 'required',
        ];

        $this->validate($request, $rules);
        $data = $request->only('serial_number','device_name','description');

        $device->fill($data);
        $device->save(); // UPDATE

        $notification = 'La informacion del Dispositivo se ha registrado correctamente';
        return redirect('/devices')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        $deviceName = $device->device_name;

        try {
            $device->delete();
            $notification = "El Dispositivo $deviceName ha sido eliminado correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion del Dispositivo no permitido ya que tiene Maquinas Asociadas."; break;
            }

            $notification = $error;
        }

        return redirect('/devices')->with(compact('notification'));
    }
}
