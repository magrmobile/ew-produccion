<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Process;

use Exception;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$supervisors = User::supervisors()->paginate(5);
        //return view('supervisors.index', compact('supervisors'));
        return view('supervisors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $processes = Process::all();
        return view('supervisors.create', compact('processes'));
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
            'username' => 'required',
            'name' => 'required',
            //'email' => 'email',
            'process_ids.*' => 'exists:processes,id',
        ];

        $this->validate($request, $rules);

        try {
            $supervisor = User::create(
                $request->only('username','name','email','shift','warehouse')
                + [
                    'role' => 'supervisor',
                    'password' => bcrypt($request->input('password'))
                ]
            );

            $supervisor->assignedProcesses()->sync($request->input('process_ids', []));

            $notification = 'El Supervisor se ha registrado correctamente';
        } catch(Exception $e) {
            $notification = $e->errorInfo[2];
        }
        
        return redirect('/supervisors')->with(compact('notification'));
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
    public function edit(User $supervisor)
    {
        $processes = Process::all();
        $selectedProcessIds = $supervisor->assignedProcesses()->pluck('processes.id')->toArray();
        return view('supervisors.edit', compact('supervisor', 'processes', 'selectedProcessIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'username' => 'required',
            'name' => 'required',
            //'email' => 'email',
            'process_ids.*' => 'exists:processes,id',
        ];

        $this->validate($request, $rules);

        $user = User::supervisors()->findOrFail($id);
        
        $data = $request->only('username','name','email','shift','warehouse');
        $password = $request->input('password');

        if($password)
            $data['password'] = bcrypt($password);

        $user->fill($data);
        $user->save(); // UPDATE
        $user->assignedProcesses()->sync($request->input('process_ids', []));

        $notification = 'La información del operador se ha registrado correctamente';
        return redirect('/supervisors')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $supervisor)
    {
        $SupervisorName = $supervisor->name;

        try {
            $supervisor->delete();
            $notification = "El Supervisor $SupervisorName ha sido eliminado correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion del Supervisor no permitido ya que tiene Paros Asociados."; break;
            }

            $notification = $error;
        }

        return redirect('/supervisors')->with(compact('notification'));
    }
}
