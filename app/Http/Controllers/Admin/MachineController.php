<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Machine;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $machines = Machine::latest()->paginate(5);
        return view('machines.index', compact('machines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('machines.create');
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
            'machine_code' => 'required',
            'warehouse' => 'required'
        ];

        $this->validate($request, $rules);

        Machine::create(
            $request->only('machine_name','machine_code','warehouse')
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
        return view('machines.edit', compact('machine'));
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
            'machine_code' => 'required',
            'warehouse' => 'required'
        ];

        $this->validate($request, $rules);
        $data = $request->only('machine_name','machine_code','warehouse');

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
        $machine->delete();

        $notification = "La Maquina $machineName ha sido eliminada correctamente";
        return redirect('/machines')->with(compact('notification'));
    }
}
