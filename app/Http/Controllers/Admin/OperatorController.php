<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

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
        return view('operators.index', compact('operators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('operators.create');
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
            'name' => 'required|min:3',
            'email' => 'required|email',
            'dni' => 'nullable|digits:8',
            'phone' => 'nullable|min:6'
        ];

        $this->validate($request, $rules);

        User::create(
            $request->only('name','email','dni','address','phone')
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
        return view('operators.edit', compact('operator'));
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
            'name' => 'required|min:3',
            'email' => 'required|email',
            'dni' => 'nullable|digits:8',
            'phone' => 'nullable|min:6'
        ];

        $this->validate($request, $rules);

        $user = User::operators()->findOrFail($id);
        
        $data = $request->only('name','email','dni','phone');
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
        $operator->delete();

        $notification = "El Operador $OperatorName ha sido eliminado correctamente";
        return redirect('/operators')->with(compact('notification'));
    }
}
