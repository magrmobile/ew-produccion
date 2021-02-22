<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Conversion;

use Exception;

class ConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversions = Conversion::paginate(5);
        return view('conversions.index', compact('conversions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('conversions.create');
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
            'factor' => 'required|float',
            'type' => 'required'
        ];

        $this->validate($request, $rules);

        Conversion::create(
            $request->only('description','factor','type')
        );

        $notification = 'La Conversion se ha registrado correctamente';
        return redirect('/conversions')->with(compact('notification'));
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
    public function edit(Conversion $conversion)
    {
        return view('conversions.edit', compact('conversion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversion $conversion)
    {
        $rules = [
            'description' => 'required|min:3',
            'factor' => 'required|float',
            'type' => 'required'
        ];

        $this->validate($request, $rules);
        $data = $request->only('description','factor','type');

        $conversion->fill($data);
        $conversion->save(); // UPDATE

        $notification = 'La información de la conversion se ha registrado correctamente';
        return redirect('/conversions')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conversion $conversion)
    {
        $conversionPackage = $conversion->package;

        try {
            $conversion->delete();
            $notification = "La Conversion $conversionPackage ha sido eliminada correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion de la Conversion no permitido ya que tiene Paros Asociados."; break;
            }

            $notification = $error;
        }

        return redirect('/conversions')->with(compact('notification'));
    }
}
