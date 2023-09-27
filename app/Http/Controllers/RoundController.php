<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Machine;
use App\Round;
use App\Product;
use App\Code;
use App\CustomNotifiable;
use App\MachineProduct;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RoundsZeroMetersNotification;

use Exception;

class RoundController extends Controller
{
    /*protected $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }*/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $rounds = $user->rounds;

        $filter = $request->get('filter', 'day');

        $date = Carbon::now();

        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');

        if($user->role == 'admin') {
            switch($filter) {
                case 'day':
                    $rounds = Round::with(['machine', 'product'])->whereDate('round_date', $newDate)->get();
                    break;
                case 'year':
                    $rounds = Round::with(['machine', 'product'])->whereYear('round_date', $date->year)->get();
                    break;
                default:
                    $rounds = Round::with(['machine', 'product'])->whereMonth('round_date', $date->month)->whereYear('round_date', $date->year)->get();
                    break;
            }
        } else {
            switch($filter) {
                case 'day':
                    $rounds = Round::where('user_id', $user->id)->whereDate('round_date', $newDate)->get();
                    break;
                case 'year':
                    $rounds = Round::where('user_id', $user->id)->whereYear('round_date', $date->year)->get();
                    break;
                default:
                    $rounds = Round::where('user_id', $user->id)->whereMonth('round_date', $date->month)->whereYear('round_date', $date->year)->get();
                    break;
            }
        }

        //print_r($newDate);

        return view('rounds.index', compact('rounds', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        $products = Product::all();
        $codes = Code::all();

        if($user->role == 'admin') {
            $machines = Machine::all();
        } else {
            $machines = Machine::where('warehouse', $user->warehouse)->orderBy('machine_name')->get();
        }

        $machine_id_missing = $request->input('machine_id');
        $hour_missing = $request->input('hour');
        $round_date_missing = $request->input('round_date');

        $current_machine_id = 0;
        $current_product_id = 0;
        $last_round = Round::where('user_id', $user->id)->latest()->get()->first();

        if($last_round) {
            $current_machine_id = $last_round->machine_id;
            $current_product_id = $last_round->product_id;
        }
        
        return view('rounds.create', [
            'machines' => $machines,
            'codes' => $codes,
            'products' => $products,
            'machine_id_missing' => $machine_id_missing,
            'hour_missing' => $hour_missing,
            'round_date_missing' => $round_date_missing,
            'current_machine_id' => $current_machine_id,
            'current_product_id' => $current_product_id
        ]);
        //return $existingRounds;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $customMessages = [
            'machine_id.required' => 'El campo máquina es requerido.',
            'produced_meters.required_without' => 'Los metros producidos son necesarios si no existe razón de no producción.',
            'produced_meters.numeric' => 'Debe ingresar un valor para metros producidos',
            'production_speed.required_with' => 'La velocidad de producción es necesaria si se han producido metros.',
            'production_speed.numeric' => 'Debe ingresar un valor para Velocidad de Produccion.',
            'product_id.required_with' => 'El producto es necesario si se han producido metros.',
            'code_id.required_without_all' => 'Es necesario indicar una razón o codigo de paro por la que no hubo producción si no se ha ingresado ninguna producción.',
            'no_production_reason.required_if' => 'Si el motivo o codigo de paro es Otros debe especificar un comentario.',
            'hour.required' => 'La hora es requerida.',
            'hour.date_format' => 'La hora debe estar en formato HH:mm',
            'round_date.required' => 'La fecha es requerida.',
            'round_date.date' => 'El formato de la fecha no es válido.',
        ];

        // Validar los datos del formulario
        $request->validate([
            'machine_id' => 'required',
            'round_date' => 'required|date',
            'hour' => 'required|date_format:H:i',
            'produced_meters' => 'numeric|required_without:code_id',
            'production_speed' => 'numeric|required_with:produced_meters',
            'product_id' => 'required_if:produced_meters,gte:0',
            'code_id' => 'required_without_all:produced_meters,production_speed,product_id|required_if:produced_meters,0',
            'no_production_reason' => 'required_if:code_id,12',

        ], $customMessages);

        // Verificar si ya existe una ronda en la misma fecha y hora
        $existingRound = Round::where('user_id', $user->id)
            ->where('machine_id', $request->machine_id)
            ->where('round_date', $request->round_date)
            ->where('hour', $request->hour)
            ->first();
    
        if ($existingRound) {
            return redirect()->back()->withErrors(['hour' => 'Ya existe una ronda para esta hora.']);
        }

        if($request->input('produced_meters') == 0) {
            $machineId = $request->machine_id;
            $roundDate = $request->round_date;

            $lastRounds = Round::where('machine_id', $machineId)
                        ->whereDate('round_date', $roundDate)
                        ->orderBy('created_at', 'desc')
                        ->take(4)
                        ->get();
            
            if($lastRounds->count() == 4 && $lastRounds->pluck('produced_meters')->sum() == 0) {
                $supEmails = ['d.perez@enerwire.com', 'm.quintanilla@enerwire.com', 'g.quetglas@enerwire.com', 'r.obyrne@enerwire.com', 'rparada@rpbsoluciones.com', 'magrmobile@gmail.com'];
                //$supEmails = ['rparada@rpbsoluciones.com', 'magrmobile@gmail.com'];

                $supNotifiables = collect($supEmails)->map(function($email) {
                    $notifiable = new CustomNotifiable();
                    $notifiable->email = $email;
                    return $notifiable;
                });
                
                $machine = Machine::find($request->machine_id);
                $shift = $user->shift;

                Notification::send($supNotifiables, new RoundsZeroMetersNotification($machine, $shift));
            }
        }

        // Crear la nueva ronda
        $round = new Round;
        $round->user_id = $user->id;
        $round->machine_id = $request->machine_id;
        $round->shift = $user->shift;
        $round->warehouse = $user->warehouse;
        $round->produced_meters = $request->produced_meters;
        $round->production_speed = $request->production_speed;
        $round->product_id = $request->product_id;
        $round->no_production_reason = $request->no_production_reason;
        $round->hour = $request->hour;
        $round->round_date = $request->round_date;
        $round->code_id = $request->code_id;

        try {
            $round->save();
            $notification = 'Ronda se ha registrado correctamente';
        } catch(Exception $e) {
            $notification = $e->errorInfo[2];
        }

        return redirect('/rounds')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $round = Round::find($id);
        return view('rounds.show', compact('round'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();

        $products = Product::all();
        $codes = Code::all();

        if($user->role == 'admin') {
            $machines = Machine::all();
        } else {
            $machines = Machine::where('warehouse', $user->warehouse)->orderBy('machine_name')->get();
        }

        $round = Round::find($id);

        return view('rounds.edit', compact('round', 'products', 'codes', 'machines'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Round $round)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Round $round)
    {
        //
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $rounds = $user->rounds;
        $controller = new RoundController();

        $filter = $request->get('filter', 'day');

        $date = Carbon::now();

        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');

        if($user->role == 'admin') {
            switch($filter) {
                case 'day':
                    $rounds = Round::with(['machine', 'product'])->whereDate('round_date', $newDate)->get();
                    break;
                case 'year':
                    $rounds = Round::with(['machine', 'product'])->whereYear('round_date', $date->year)->get();
                    break;
                default:
                    $rounds = Round::with(['machine', 'product'])->whereMonth('round_date', $date->month)->whereYear('round_date', $date->year)->get();
                    break;
            }
        } else {
            switch($filter) {
                case 'day':
                    $rounds = Round::where('user_id', $user->id)->whereDate('round_date', $newDate)->get();
                    break;
                case 'year':
                    $rounds = Round::where('user_id', $user->id)->whereYear('round_date', $date->year)->get();
                    break;
                default:
                    $rounds = Round::where('user_id', $user->id)->whereMonth('round_date', $date->month)->whereYear('round_date', $date->year)->get();
                    break;
            }
        }

        //print_r($newDate);

        $hourlyEfficiencies = $this->getHourlyEfficiencies(date('m'), date('Y'), $filter);

        $machineIds = $rounds->pluck('machine_id')->unique();
        $machines = Machine::findMany($machineIds);

        $missingRounds = Round::getMissingRounds();

        return view('rounds.dashboard', compact('rounds', 'controller', 'hourlyEfficiencies', 'filter', 'machines', 'user', 'missingRounds'));
    }

    public function getMachineHours(Request $request)
    {
        $user = auth()->user();
        $machineId = $request->machine_id;
        $date = $request->date;

        $hour_now = date("H");

        if($hour_now >= 7 && $hour_now <= 18) {
            $shift = 'D';
        } else {
            $shift = 'N';
        }

        // Obtén las horas del turno para el usuario autenticado
        $hours = $user->getShiftHours($shift);

        // Obtén las rondas existentes para la máquina y la fecha seleccionadas
        // $rounds = Round::where('user_id', $user->id)->where('machine_id', $machineId)->where('round_date', $date)->get();
        $rounds = Round::where('machine_id', $machineId)->where('round_date', $date)->get();

        // Construye la matriz $existingRounds
        $existingRounds = [];
        foreach ($rounds as $round) {
            $existingRounds[substr($round->hour,0,5)] = $round;
        }

        return view('rounds.hour-buttons', [
            'hours' => $hours,
            'existingRounds' => $existingRounds,
        ])->render();
        //return $existingRounds;
    }

    public function overallEfficiency()
    {
        $user = auth()->user();

        if($user->role == 'admin') {
            $rounds = Round::all();
        } else {
            $rounds = Round::where('user_id', $user->id)->get();
        }

        $totalEfficiency = 0;
        foreach($rounds as $round) {
            $totalEfficiency += $round->percentage_achievement;
        }
        return $rounds->count() ? round($totalEfficiency / $rounds->count(),2) : 0;
    }

    public function monthlyEfficiency($month, $year)
    {
        $user = auth()->user();

        if($user->role == 'admin') {
            $rounds = Round::whereMonth('round_date', $month)->whereYear('round_date', $year)->get();
        } else {
            $rounds = Round::where('user_id', $user->id)->whereMonth('round_date', $month)->whereYear('round_date', $year)->get();
        }

        $totalEfficiency = 0;
        foreach ($rounds as $round) {
            $totalEfficiency += $round->percentageAchievement;
        }
        return $rounds->count() ? round($totalEfficiency / $rounds->count(),2) : 0;
    }

    public function efficiencyChange($month, $year)
    {
        $currentMonthEfficiency = $this->monthlyEfficiency($month, $year);
        $lastMonthEfficiency = $this->monthlyEfficiency($month - 1, $year);
        return $lastMonthEfficiency ? ($currentMonthEfficiency - $lastMonthEfficiency) / $lastMonthEfficiency * 100 : 0;
    }

    public function getHourlyEfficiencies($month, $year, $filter)
    {
        $user = auth()->user();

        $date = Carbon::now();

        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');

        if($user->role == 'admin') {
            switch($filter) {
                case 'day':
                    $rounds = Round::whereDate('round_date', $newDate)->get();
                    break;
                case 'year':
                    $rounds = Round::whereYear('round_date', $date->year)->get();
                    break;
                default:
                    $rounds = Round::whereMonth('round_date', $date->month)->whereYear('round_date', $date->year)->get();
                    break;
            }
            //$rounds = Round::whereMonth('round_date', $month)->whereYear('round_date', $year)->get();
        } else {
            switch($filter) {
                case 'day':
                    $rounds = Round::where('user_id', $user->id)->whereDate('round_date', $newDate)->get();
                    break;
                case 'year':
                    $rounds = Round::where('user_id', $user->id)->whereYear('round_date', $date->year)->get();
                    break;
                default:
                    $rounds = Round::where('user_id', $user->id)->whereMonth('round_date', $date->month)->whereYear('round_date', $date->year)->get();
                    break;
            }
            //$rounds = Round::where('user_id', $user->id)->whereMonth('round_date', $month)->whereYear('round_date', $year)->get();
        }

        $hour_now = date("H");

        if($hour_now >= 8 && $hour_now <= 18) {
            $shift = 'D';
        } else {
            $shift = 'N';
        }

        // Obtener las horas correspondientes al turno del usuario
        $shiftHours = $user->getShiftHours($shift);

        // Inicializar un array para contener las eficiencias por hora
        $hourlyEfficiencies = array_fill_keys($shiftHours, 0);
        $hourCounts = array_fill_keys($shiftHours, 0);

        foreach ($rounds as $round) {
            $hour = Carbon::parse($round->hour)->format('H:i');
            if (in_array($hour, $shiftHours)) {
                $hourlyEfficiencies[$hour] += $round->percentageAchievement;
                $hourCounts[$hour]++;
            }
        }

        // Calcular el promedio de eficiencia para cada hora
        foreach ($hourlyEfficiencies as $hour => $totalEfficiency) {
            $hourlyEfficiencies[$hour] = $hourCounts[$hour] ? round($totalEfficiency / $hourCounts[$hour],2) : 0;
        }

        return $hourlyEfficiencies;
    }

    public function missingRounds()
    {
        $missingRounds = Round::getMissingRounds();
        return view('rounds.missing', ['missingRounds' => $missingRounds]);
    }

    public function getProductionSpeed(Request $request)
    {
        $machineId = $request->input('machine_id');
        $productId = $request->input('product_id');

        $machine = Machine::find($machineId);
        $product = Product::find($productId);

        $speed = $machine->products()->wherePivot('product_id', $product->id)->first()->pivot->speed;

        return response()->json(['speed' => $speed]);
    }

    public function getLastRoundProduct(Request $request) {
        $machineId = $request->input('machine_id');

        $round = Round::where('machine_id', $machineId)->latest()->first();

        return response()->json([
            'product_id' => $round->product_id,
            'code_id' => $round->code_id 
        ]);
    }

}