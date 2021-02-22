<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Family;

use Exception;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $families = Family::paginate(5);
        return view('families.index', compact('families'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('families.create');
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
            'family_name' => 'required',
        ];

        $this->validate($request, $rules);

        Family::create(
            $request->only('family_name')
        );

        $notification = 'La Familia de Producto se ha registrado correctamente';
        return redirect('/families')->with(compact('notification'));
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
    public function edit(Family $family)
    {
        return view('families.edit', compact('family'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Family $family)
    {
        $rules = [
            'family_name' => 'required',
        ];

        $this->validate($request, $rules);
        $data = $request->only('family_name');

        $family->fill($data);
        $family->save(); // UPDATE

        $notification = 'La informacion de la Familia se ha registrado correctamente';
        return redirect('/families')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Family $family)
    {
        $familyName = $family->family_name;

        try {
            $family->delete();
            $notification = "la Familia $familyName ha sido eliminada correctamente";
        } catch(Exception $e) {
            $error = "";
            
            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion de la Familia no permitido ya que tiene Productos Asociados."; break;
            }

            $notification = $error;
        }

        return redirect('/families')->with(compact('notification'));
    }
}
