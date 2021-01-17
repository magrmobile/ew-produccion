<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Color;

use Exception;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::latest()->paginate(5);
        return view('colors.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('colors.create');
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
            'hex_code' => ['required','regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/']
        ];

        $this->validate($request, $rules);

        Color::create(
            $request->only('name','hex_code')
        );

        $notification = 'El Color se ha registrado correctamente';
        return redirect('/colors')->with(compact('notification'));
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
    public function edit(Color $color)
    {
        return view('colors.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Color $color)
    {
        $rules = [
            'name' => 'required|min:3',
            'hex_code' => ['required','regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/']
        ];

        $this->validate($request, $rules);
        $data = $request->only('name','hex_code');

        $color->fill($data);
        $color->save(); // UPDATE

        $notification = 'El color se ha registrado correctamente';
        return redirect('/colors')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        $colorName = $color->color;

        try {
            $color->delete();
            $notification = "El Color $colorName ha sido eliminado correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion del Color no permitido ya que tiene Paros Asociados."; break;
            }

            $notification = $error;
        }

        return redirect('/colors')->with(compact('notification'));
    }
}
