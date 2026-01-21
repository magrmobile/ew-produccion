<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

use DataTables;

use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Support\MessageBag;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $codigos_actividad = DB::table('cat019')->get();
        $tipos_establecimiento = DB::table('cat009')->get();
        $departamentos = DB::table('cat012')->get();
        $municipios = DB::table('cat013')->get();
        $distritos = 
        $codigos_pais = DB::table('cat020')->get();
        $codigos_domiciliado = DB::table('cat032')->get();
        $bienes_titulo = DB::table('cat025')->get();
        $tipos_persona = DB::table('cat029')->get();
        $categories = DB::table('customer_categories')->get();
        $tipo_documento = DB::table('cat022')->get();

        return view('customers.create',[
            'codigos_actividad' => $codigos_actividad,
            'tipos_establecimiento' => $tipos_establecimiento,
            'departamentos' => $departamentos,
            'municipios' => $municipios,
            'codigos_pais' => $codigos_pais,
            'codigos_domiciliado' => $codigos_domiciliado,
            'bienes_titulo' => $bienes_titulo,
            'tipos_persona' => $tipos_persona,
            'categories' => $categories,
            'tipo_documento' => $tipo_documento,
        ]);
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
        ];

        $this->validate($request, $rules);

        try {
            Customer::create([
                'nit' => $request->nit,
                'nrc' => $request->nrc,
                'nombre' => $request->nombre,
                'codActividad' => $request->codActividad,
                'nombreComercial' => $request->nombreComercial,
                'tipoEstablecimiento' => $request->tipoEstablecimiento,
                'departamento' => $request->departamento,
                'municipio' => $request->municipio,
                'complemento' => $request->complemento,
                'codPais' => $request->codPais,
                'codDomiciliado' => $request->codDomiciliado,
                'codigoMH' => $request->codigoMH,
                'puntoVentaMH' => $request->puntoVentaMH,
                'bienTitulo' => $request->bienTitulo,
                'tipoPersona' => $request->tipoPersona,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'created_by' => auth()->user()->id,
                'nombre_contacto' => $request->nombre_contacto,
                'tipodoc_contacto' => $request->tipodoc_contacto,
                'numdoc_contacto' => $request->numdoc_contacto
            ]);



            $notification = 'El Cliente se ha registrado correctamente';
            return redirect('/customers')->with(compact('notification'));
        } catch(Exception $e) {
            $errors = new MessageBag([$e->errorInfo[2]]);
            //dd($errors);
            return redirect()->route('customers.create')->with(compact('errors'));
        }
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
    public function edit(Customer $customer)
    {
        $codigos_actividad = DB::table('cat019')->get();
        $tipos_establecimiento = DB::table('cat009')->get();
        $departamentos = DB::table('cat012')->get();
        $municipios = DB::table('cat013')->get();
        $codigos_pais = DB::table('cat020')->get();
        $codigos_domiciliado = DB::table('cat032')->get();
        $bienes_titulo = DB::table('cat025')->get();
        $tipos_persona = DB::table('cat029')->get();
        $categories = DB::table('customer_categories')->get();
        $tipo_documento = DB::table('cat022')->get();
        
        return view('customers.edit',compact(
            'customer',
            'codigos_actividad',
            'tipos_establecimiento',
            'departamentos',
            'municipios',
            'codigos_pais',
            'codigos_domiciliado',
            'bienes_titulo',
            'tipos_persona',
            'categories',
            'tipo_documento')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        try {
            $customer->fill($request->post())->save();
            $notification = "Cliente Actualizado Satisfactoriamente";
        }catch(Exception $e) {
            $notification = $e->errorInfo[2];
        }
        return redirect('/customers')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
