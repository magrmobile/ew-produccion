<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Machine;
use App\Product;
use App\Code;
use App\Color;
use Carbon\Carbon;
use App\Stop;
use App\Conversion;
use App\Http\Requests\StoreStop;

use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\StopsExport;

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
        $operatorId = auth()->user()->id;

        if($role == 'operator') {
            $stops = Stop::whereOperatorId($operatorId)
            ->whereDate('stop_datetime_end','>=',Carbon::now()->subDays(2)->toDateString())
            ->orderBy('stop_datetime_end','ASC')->paginate(5);
        }
        
        if($role == 'supervisor') {
            $stops = Stop::with(['operator' => function($query) use ($operatorId) {
                $query->where('supervisor_id','=',$operatorId);
            }])->paginate(5);
        }

        if($role == 'admin'){
            $stops = Stop::paginate(5);
        }

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
        $conversions = Conversion::all();
        $lastStop = Stop::where('operator_id', auth()->user()->id)
                        //->where('machine_id', $machine_id)
                        ->latest('id')
                        ->first();
        if($lastStop) {
            $dateInit = $lastStop->stop_datetime_end;
        } else {
            $dateInit = auth()->user()->lastLoginAt();
        }
        
        return view('stops.create', compact('machines','products','codes','colors','dateInit','conversions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStop $request)
    {
    
        $created = Stop::createForOperator($request, auth()->id());

        if($created)
            $notification = 'El Paro se ha registrado correctamente';
        else
            $notification = 'Ocurrio un problema al registrar el paro';

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
        Carbon::setLocale('es');

        $role = auth()->user()->role;

        if($stop->stop_datetime_end != null){
            $start = new Carbon($stop->stop_datetime_start);
            $end = new Carbon($stop->stop_datetime_end);

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
        $conversions = Conversion::all();
        return view('stops.edit', compact('stop','machines','products','codes','colors','conversions'));
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
                    'color_id' => 'exists:colors,id|required'
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
    public function destroy(Stop $stop)
    {
        $stopId = $stop->id;

        try {
            $stop->delete();
            $notification = "El Paro $stopId ha sido eliminado correctamente";
        } catch(Exception $e) {
            $error = "";

            switch($e->getCode()) {
                case 23000 : $error = "Eliminacion del Paro no permitido";
            }

            $notification = $error;
        }

        return redirect('/stops')->with(compact('notification'));
    }

    public function exportPdf() 
    {
        $stops = Stop::get();
        $pdf = PDF::loadView('pdf.stops', compact('stops'));

        return $pdf->download('stops-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new StopsExport, 'stop-list.xlsx');
    }
}
