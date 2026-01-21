<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\User;
use App\Machine;
use App\Family;
use App\Code;
use App\Process;
use App\Conversion;
use App\Customer;
use App\dte;

class DatatableController extends Controller
{
    public function code() {
        $codes = Code::all();
        return datatables()->of($codes)
            ->addColumn('action', function($code){
                $btn = '
                    <a href="'.route('codes.edit', $code->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$code->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            ->make(true);
    }

    public function conversion() {
        $conversions = Conversion::all();
        return datatables()->of($conversions)
            ->addColumn('action', function($conversion){
                $btn = '
                    <a href="'.route('conversions.edit', $conversion->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$conversion->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            ->make(true);
    }

    public function process() {
        $processes = Process::all();
        return datatables()->of($processes)
            ->addColumn('action', function($process){
                $btn = '
                    <a href="'.route('processes.edit', $process->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$process->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            ->make(true);
    }

    public function customer() {
        $customers = Customer::all();
        return datatables()->of($customers)
            ->addColumn('action', function($customer){
                $btn = '
                    <a href="'.route('customers.edit', $customer->id).'" class="btn btn-primary" title="Editar"><i class="fas fa-edit"></i></a>
                    <a href="'.route('dtes.index', ['customer_id' => $customer->id]).'" class="btn btn-warning" title="DTE"><i class="fas fa-file-invoice"></i></a>
                ';
                /*$btn = '
                    <a href="'.route('customers.show', $customer->id).'" class="btn btn-success" title="Ver"><i class="fas fa-eye"></i></a>
                    <a href="'.route('customers.edit', $customer->id).'" class="btn btn-primary" title="Editar"><i class="fas fa-edit"></i></a>
                    <a href="'.route('dtes.index', ['customer_id' => $customer->id]).'" class="btn btn-warning" title="DTE"><i class="fas fa-file-invoice"></i></a>
                ';*/
                return $btn;
            })
            ->make(true);
    }

    public function dte($customer_id) {
        $dtes = dte::where('customer_id', $customer_id)->orderBy('id','desc');
        return datatables()->of($dtes)
            ->addColumn('signed', function($dte){
                if($dte->signed == 1){
                    return '<center><i class="fas fa-circle text-success" style="font-size:24px"></i></center>';
                } else {
                    return '<center>
                        <a href="'.route('dtes.signDte',$dte-> id).'" class="btn-ico"><i class="fas fa-circle text-danger" style="font-size:24px"></i></a>
                        </center>';
                }
            })
            ->addColumn('received', function($dte){
                if($dte->received == 1){
                    return '<i class="fas fa-circle text-success" style="font-size:24px"></i>';
                } else {
                    return '<a href="'.route('dtes.sendDte',$dte->id).'" class="btn-ico"><i class="fas fa-circle text-danger" style="font-size:24px"></i></a>';
                }
            })
            ->addColumn('invalidate', function($dte){
                if($dte->received == 1) {
                    if($dte->invalidate == 0) {
                        return '<a href="'.route('dtes.invalidate',$dte).'" class="btn-ico"><i class="fas fa-times-circle text-danger" style="font-size:24px"></i></a>';
                    } else {
                        return '<i class="fas fa-circle text-success" style="font-size:24px"></i>';
                    }
                }
            })
            ->addColumn('action', function($dte){
                $signed = $dte->signed;
                if($signed == 1) {
                    $class_signed = "disabled";
                }

                $actions = '
                <a href="'.route('dtes.show', $dte).'" class="btn btn-sm btn-primary" role="button" title="Ver"><i class="fas fa-eye"></i></a>
                ';
                /*$actions = '
                <a href="'.route('dtes.show', $dte->id).'" class="btn btn-sm btn-primary" role="button" title="Ver"><i class="fas fa-eye"></i></a>
                <a href="'.route('dtes.signDte', $dte->id).'" class="btn btn-sm btn-secondary {{ $class_signed }}" role="button" title="Firmar"><i class="fas fa-pen-nib"></i></a>
                ';*/
                return $actions;
            })
            ->rawColumns(['signed', 'received', 'invalidate', 'action'])
            ->make(true);
    }

    public function product() {
        //$products = Product::all();
        //$products->makeHidden(['family','process']);
        $products = DB::table('products')
                    ->leftJoin('families','products.family_id','=','families.id')
                    ->leftJoin('processes','products.process_id','=','processes.id')
                    ->select(
                        'products.id',
                        'products.product_name',
                        'products.metal_type',
                        'families.family_name',
                        'products.product_name',
                        'processes.description as process_name',
                        'products.stock'
                    )
                    ->get();
        return datatables()->of($products)
            ->addColumn('action', function($product){
                $btn = '
                    <a href="'.route('products.edit', $product->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$product->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            /*->addColumn('action', function($product){
                $btn = '
                    <a href="'.route('products.edit', $product->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <form action="'.route('products.destroy',$product->id).'" method="POST" class="d-inline form-delete">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                    </form>';
                return $btn; 
                
            })*/
            ->make(true);
    }

    public function operator() {
        //$operators = User::operators();
        $operators = DB::table('users as operators')
                     ->leftJoin('processes','operators.process_id','=','processes.id')
                     ->leftJoin('users as supervisors','operators.supervisor_id','=','supervisors.id')
                     ->select(
                         'operators.id',
                         'operators.username',
                         'operators.name',
                         'supervisors.name as supervisor',
                         'processes.description as process_name',
                         'operators.active'
                     )
                     ->get();
        return datatables()->of($operators)
            ->addColumn('action', function($operator){
                $btn = '
                    <a href="'.route('operators.edit', $operator->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$operator->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            ->make(true);
    }

    public function supervisor() {
        $supervisors = User::supervisors();
        return datatables()->of($supervisors)
            ->addColumn('action', function($supervisor){
                $btn = '
                    <a href="'.route('supervisors.edit', $supervisor->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$supervisor->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            ->make(true);
    }

    public function machine() {
        $machines = Machine::all();
        $machines->makeHidden(['process']);
        return datatables()->of($machines)
            ->addColumn('action', function($machine){
                $btn = '
                    <a href="'.route('machines.edit', $machine->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$machine->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            ->make(true);
    }

    public function family() {
        $families = Family::all();
        return datatables()->of($families)
            ->addColumn('action', function($family){
                $btn = '
                    <a href="'.route('families.edit', $family->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$family->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            ->make(true);
    }

    public function stop(Request $request) {
         //$stops = Stop::all();
         $stops = DB::table('stops')
                ->leftJoin('users','stops.operator_id','=','users.id')
                ->leftJoin('machines','stops.machine_id','=','machines.id')
                ->leftJoin('products','stops.product_id','=','products.id')
                ->leftJoin('processes','machines.process_id','=','processes.id')
                ->leftJoin('codes','stops.code_id','=','codes.id')
                ->select(
                    'stops.id',
                    'stops.stop_datetime_end',
                    'users.name as operator_name',
                    'codes.code as code',
                    'products.product_name as product_name',
                    'machines.machine_name'
                )
                ->whereRaw('DATE(stops.stop_datetime_end) BETWEEN (CURRENT_DATE() - INTERVAL 6 MONTH) AND CURRENT_DATE()')
                ->orderBy('stops.stop_datetime_end','desc')
                ->get();
         return datatables()->of($stops)
            ->addColumn('action', function($stop){
                $btn = '
                    <a href="'.route('stops.edit', $stop->id).'" class="btn btn-sm btn-primary">Editar</a>
                    <button onclick="deleteData('.$stop->id.')" class="btn btn-sm btn-danger">Eliminar</button>';
                return $btn; 
                
            })
            ->make(true);
    }
}
