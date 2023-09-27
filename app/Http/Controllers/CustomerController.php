<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

use DataTables;

use Illuminate\Support\Facades\DB;

use Exception;

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
        $codigos_pais = DB::table('cat020')->get();
        $codigos_domiciliado = DB::table('cat032')->get();
        $bienes_titulo = DB::table('cat025')->get();
        $tipos_persona = DB::table('cat029')->get();
        $categories = DB::table('customer_categories')->get();

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
            Customer::create(
                $request->only('nit','nrc','nombre','codActividad','nombreComercial','tipoEstablecimiento','departamento','municipio','complemento','codPais','codDomiciliado','codigoMH','puntoVentaMH','bienTitulo','tipoPersona','telefono','correo')
            );

            $notification = 'El Cliente se ha registrado correctamente';
        } catch(Exception $e) {
            $notification = $e->errorInfo[2];
        }
        
        return redirect('/customers')->with(compact('notification'));
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
            'categories')
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
