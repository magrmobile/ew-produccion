<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Code;

use Exception;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $codes = Code::paginate(5);
        return view('codes.index', compact('codes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('codes.create');
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
            'type' => 'required'
        ];

        $this->validate($request, $rules);

        Code::create(
            $request->only('description','type')
        );

        $notification = 'El Motivo se ha registrado correctamente';
        return redirect('/codes')->with(compact('notification'));
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
    public function edit(Code $code)
    {
        return view('codes.edit', compact('code'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Code $code)
    {
        $rules = [
            'description' => 'required|min:3',
            'type' => 'required'
        ];

        $this->validate($request, $rules);
        $data = $request->only('description','type');

        $code->fill($data);
        $code->save(); // UPDATE

        $notification = 'La información del motivo se ha registrado correctamente';
        return redirect('/codes')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Code $code)
    {
        $codeDesc = $code->description;
        
        try {
            $code->delete();
            $notification = "El Motivo $codeDesc ha sido eliminado correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion del Motivo no permitido ya que tiene Paros Asociados."; break;
            }

            $notification = $error;
        }

        return redirect('/codes')->with(compact('notification'));
    }
}
