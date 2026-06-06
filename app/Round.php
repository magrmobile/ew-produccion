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

    public static function getMissingRounds()
    {
        return self::getMissingRoundsForLast24Hours();
    }

    public static function getMissingRoundsForLast24Hours($machineId = null, $date = null)
    {
        $machines = $machineId ? Machine::where('id', $machineId)->get() : Machine::all();
        $slots = self::last24HourSlots($date);
        $missingRounds = collect();

        foreach ($machines as $machine) {
            foreach ($slots as $slot) {
                $round = Round::where('machine_id', $machine->id)
                    ->where('round_date', $slot['round_date'])
                    ->where('hour', $slot['hour'])
                    ->first();

                if (!$round) {
                    $missingRounds->push([
                        'machine_id' => $machine->id,
                        'machine_name' => $machine->machine_name,
                        'round_date' => $slot['round_date'],
                        'hour' => $slot['hour'],
                    ]);
                }
            }
        }

        return $missingRounds;
    }

    private static function last24HourSlots($date = null)
    {
        $windowEnd = $date
            ? Carbon::createFromFormat('Y-m-d H:i:s', $date . ' 23:00:00')
            : Carbon::now()->minute(0)->second(0);
        $cursor = $windowEnd->copy()->subHours(23);
        $slots = collect();

        while ($cursor->lte($windowEnd)) {
            $slots->push([
                'round_date' => $cursor->format('Y-m-d'),
                'hour' => $cursor->format('H:00'),
            ]);

            $cursor->addHour();
        }

        return $slots;
    }
}
