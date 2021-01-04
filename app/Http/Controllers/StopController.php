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
        $stops = Stop::where('stop_date_end',date('Y-m-d'))->orderBy('stop_time_end','ASC')->paginate(5);

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
        $code_id = Code::find($request['code_id'])->code;
        //dd($lastLogin);

        switch($code_id) {
            case 0:
                $rules = [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                    'product_id' => 'exists:products,id|required',
                    'color_id' => 'exists:colors,id|required',
                    'meters' => 'required',
                ];
            break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                $rules = [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                ];
            break;
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
                $rules = [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                    'comment' => 'required'
                ];
            break;
            default:
                $rules = [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                ];
            break;
        }
        
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

        $data['stop_date_end'] = $current_date;
        $data['stop_time_end'] = $current_time;

        $lastLogin = Carbon::createFromFormat('Y-m-d H:i:s',auth()->user()->lastLoginAt());

        $lastLoginDate = $lastLogin->format('Y-m-d');
        $lastLoginTime = $lastLogin->format('H:i:s');

        $lastStop = Stop::where('operator_id',auth()->id())
                        ->latest('id')
                        ->first();

        //dd($lastStop);

        if($lastStop == null) {
            $data['stop_date_start'] = $lastLoginDate;
            $data['stop_time_start'] = $lastLoginTime;
        } else {
            if($lastStop->stop_date_start == null) {
                Stop::where('id',$lastStop->id)->update(['stop_date_start' => $current_date, 'stop_time_start' => $current_time]);
            } else {
                $data['stop_date_start'] = $lastStop->stop_date_end;
                $data['stop_time_start'] = $lastStop->stop_time_end;
                //dd($data);
            }
            //dd($lastStop->stop_date_start);
        }

        //dd($data);

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
    public function show(Stop $stop)
    {
        $role = auth()->user()->role;

        if($stop->stop_date_end != null){
            $start = new Carbon($stop->stop_date_start.' '.$stop->stop_time_start);
            $end = new Carbon($stop->stop_date_end.' '.$stop->stop_time_end);

            $duration = $start->diff($end)->format('%H:%I:%S');
        } else {
            $duration = null;
        }

        return view('stops.show', compact('stop', 'role', 'duration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Stop $stop)
    {
        $machines = Machine::all();
        $products = Product::all();
        $codes = Code::all();
        $colors = Color::all();
        return view('stops.edit', compact('stop','machines','products','codes','colors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stop $stop)
    {
        $code_id = Code::find($request['code_id'])->code;

        switch($code_id) {
            case 0:
                $rules = [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                    'product_id' => 'exists:products,id|required',
                    'color_id' => 'exists:colors,id|required',
                    'meters' => 'required',
                ];
            break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                $rules = [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                ];
            break;
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
                $rules = [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                    'comment' => 'required'
                ];
            break;
            default:
                $rules = [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                ];
            break;
        }
        
        $this->validate($request, $rules);

        $data = $request->only([
            'machine_id',
            'product_id',
            'color_id',
            'code_id',
            'meters',
            'comment',
        ]);

        $stop->fill($data);
        $stop->save(); // UPDATE

        $notification = 'La informacion del Paro se ha registrado correctamente';
        return redirect('/stops')->with(compact('notification'));
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
