<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Machine;
use App\Device;
use App\Process;

use Exception;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $machines = Machine::paginate(5);
        return view('machines.index', compact('machines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $devices = Device::all();
        $processes = Process::all();
        return view('machines.create', compact('devices','processes'));
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
            'machine_name' => 'required|min:3',
            'process_id' => 'required',
            'warehouse' => 'required',
            'device_id' => 'required|exists:devices,id'
        ];

        $this->validate($request, $rules);

        Machine::create(
            $request->only('machine_name','process_id','warehouse','device_id')
        );

        $notification = 'La Maquina se ha registrado correctamente';
        return redirect('/machines')->with(compact('notification'));
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
    public function edit(Machine $machine)
    {
        $devices = Device::all();
        $processes = Process::all();
        return view('machines.edit', compact('machine','devices','processes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Machine $machine)
    {
        $rules = [
            'machine_name' => 'required|min:3',
            'process_id' => 'required',
            'warehouse' => 'required',
            'device_id' => 'required|exists:devices,id'
        ];

        $this->validate($request, $rules);
        $data = $request->only('machine_name','process_id','warehouse','device_id');

        $machine->fill($data);
        $machine->save(); // UPDATE

        $notification = 'La información de la maquina se ha registrado correctamente';
        return redirect('/machines')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Machine $machine)
    {
        $machineName = $machine->machine_name;
        try {
            $machine->delete();
            $notification = "La Maquina $machineName ha sido eliminada correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion de Maquina no permitido ya que tiene Paros Asociados."; break;
            }

            $notification = $error;
        }
        
        return redirect('/machines')->with(compact('notification'));
    }
}
