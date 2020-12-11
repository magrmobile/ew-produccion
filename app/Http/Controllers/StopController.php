<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Machine;
use App\Product;
use App\Code;
use App\Color;
use Carbon\Carbon;
use App\Stop;

class StopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = auth()->user()->role;
        $stops = Stop::latest()->paginate(5);

        return view('stops.index', compact('stops','role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $machines = Machine::all();
        $products = Product::all();
        $codes = Code::all();
        $colors = Color::all();
        return view('stops.create', compact('machines','products','codes','colors'));
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
            'machine_id' => 'exists:machines,id',
            'product_id' => 'exists:products,id',
            'code_id' => 'exists:codes,id',
        ];

        $this->validate($request, $rules);

        $data = $request->only([
            'machine_id',
            'product_id',
            'color_id',
            'code_id',
            'meters',
            'comment',
        ]);

        $data['operator_id'] = auth()->id();

        $date = Carbon::now();
        $current_date = $date->format('Y-m-d');
        $current_time = $date->format('H:i:s');

        $data['stop_date_start'] = $current_date;
        $data['stop_time_start'] = $current_time;


        //dd($data);

        $lastStop = Stop::where('operator_id',auth()->id())->max('id');
        Stop::where('id',$lastStop)->update(['stop_date_end' => $current_date, 'stop_time_end' => $current_time]);

        //dd($lastStop);

        Stop::create($data);

        $notification = 'El Paro se ha registrado correctamente';
        return redirect('/stops')->with(compact('notification'));
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
    public function edit($id)
    {
        //
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
        //
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
