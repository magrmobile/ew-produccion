<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Machine;
use App\Round;
use Carbon\Carbon;
use App\User;
use App\Notifications\RoundMissingNotification;

class CheckMissingRounds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:missingrounds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para verificar las Rondas Faltantes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $machines = Machine::all();
        $users = User::where('role', 'supervisor')->get();

        // Calcular la fecha y hora límite (24 horas antes de ahora)
        // $date = Carbon::now()->subHours(24);
        $date = Carbon::now();

        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');

        // Obtener las horas del turno del usuario
        $shiftHours = $this->getShiftHours();

        $rounds_pending = 0;
        
        foreach ($machines as $machine) {
            foreach ($shiftHours as $hour) {
                // Formatear la hora para que coincida con el formato de la base de datos
                $formattedHour = $hour.':00';

                // Buscar una ronda para esta máquina y esta hora que sea más reciente que la fecha y hora de corte
                $round = Round::where('machine_id', $machine->id)
                            ->where('hour', $formattedHour)
                            ->where('round_date', '>=', $newDate)
                            ->first();

                if (!$round) {
                    /*foreach($users as $user) {
                        $user->notify(new RoundMissingNotification($machine, $newDate->toDateString(), $formattedHour));    
                    }*/
                    $rounds_pending++;
                }
            }
        }

        foreach($users as $user) {
            $user->notify(new RoundMissingNotification($rounds_pending, $newDate));
        }
    }

    public function getShiftHours()
    {
        $hours = [];

        for ($i=0; $i < 24; $i++) { 
            $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        }

        return $hours;
    }
}
