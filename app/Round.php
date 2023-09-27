<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Round extends Model
{
    protected $fillable = [
        'user_id', 'machine_id', 'shift', 'warehouse', 'produced_meters', 'production_speed', 'product_id', 'no_production_reason', 'hour', 'round_date'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPercentageAchievementAttribute()
    {
        $factor = 60;
        $achieved = $this->production_speed * $factor;
        if($achieved > 0) {
            return ($this->produced_meters / $achieved) * 100;
        }
    }

    public static function getMissingRounds() {
        // Obtener todas las maquinas
        $user = auth()->user();
        $machines = Machine::all();

        // Calcular la fecha y hora límite (24 horas antes de ahora)
        //$date = Carbon::now()->subHours(24);
        $date = Carbon::now();

        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
        
        $hour_now = date("H");

        if($hour_now >= 8 && $hour_now <= 18) {
            $shift = 'D';
        } else {
            $shift = 'N';
        }

        // Obtener las horas del turno del usuario
        $shiftHours = $user->getShiftHours($shift);

        $missingRounds = collect();

        foreach($machines as $machine) {
            foreach($shiftHours as $hour) {
                // Formatear la hora para que coincida con el formato de la base de datos
                $formattedHour = $hour.':00';
                
                $round = Round::where('machine_id', $machine->id)
                    ->where('round_date', $newDate)
                    ->where('hour', $formattedHour)
                    ->where('shift', $user->shift)
                    ->first();

                // Si no existe la ronda para la maquina, fecha y hora la agregamos a las horas faltantes
                if(!$round) {
                    $missingRounds->push([
                        'machine_id' => $machine->id,
                        'machine_name' => $machine->machine_name,
                        'round_date' => $newDate,
                        'hour' => $hour,
                    ]);
                }
            }
        }

        return $missingRounds;
    }
}
