<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Process;

use Exception;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operators = User::operators()->paginate(5);
        //$operators = User::all();
        return view('operators.index', compact('operators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $processes = Process::all();
        $supervisors = User::supervisors()->get();
        return view('operators.create',compact('processes','supervisors'));
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
            'email' => 'email',
            'process_id' => 'required'
        ];

        $this->validate($request, $rules);

        User::create(
            $request->only('username','name','email','process_id','supervisor_id')
            + [
                'role' => 'operator',
                'password' => bcrypt($request->input('password'))
            ]
        );

        $notification = 'El Operador se ha registrado correctamente';
        return redirect('/operators')->with(compact('notification'));
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
    public function edit(User $operator)
    {
        $processes = Process::all();
        $supervisors = User::supervisors()->get();
        return view('operators.edit', compact('operator','processes','supervisors'));
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
            'username' => 'required|min:8',
            'name' => 'required|min:3',
            'email' => 'email',
            'process_id' => 'required'
        ];

        $this->validate($request, $rules);

        $user = User::operators()->findOrFail($id);
        
        $data = $request->only('username','name','email','process_id','supervisor_id');
        $password = $request->input('password');

        if($password)
            $data['password'] = bcrypt($password);

        $user->fill($data);
        $user->save(); // UPDATE

        $notification = 'La información del operador se ha registrado correctamente';
        return redirect('/operators')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $operator)
    {
        $OperatorName = $operator->name;

        try {
            $operator->delete();
            $notification = "El Operador $OperatorName ha sido eliminado correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion del Operador no permitido ya que tiene Paros Asociados."; break;
            }

            $notification = $error;
        }

        return redirect('/operators')->with(compact('notification'));
    }
}
