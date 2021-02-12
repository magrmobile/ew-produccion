<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Process;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $processes = Process::latest()->paginate(5);
        return view('processes.index', compact('processes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('processes.create');
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
            'description' => 'required|min:3',
        ];

        $this->validate($request, $rules);

        Process::create(
            $request->only('description')
        );

        $notification = 'El Proceso se ha registrado correctamente';
        return redirect('/processes')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function show(Process $process)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function edit(Process $process)
    {
        return view('processes.edit', compact('process'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Process $process)
    {
        $rules = [
            'description' => 'required|min:3'
        ];

        $this->validate($request, $rules);
        $data = $request->only('description');

        $process->fill($data);
        $process->save(); // UPDATE

        $notification = 'El Proceso se ha registrado correctamente';
        return redirect('/processes')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function destroy(Process $process)
    {
        $processName = $process->description;

        try {
            $process->delete();
            $notification = "El Proces $processName ha sido eliminado correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion del Proceso no permitido ya que tiene Paros Asociados."; break;
            }

            $notification = $error;
        }

        return redirect('/processes')->with(compact('notification'));
    }
}
