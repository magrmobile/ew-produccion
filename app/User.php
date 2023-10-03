<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Yadahan\AuthenticationLog\AuthenticationLogable;

use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable, AuthenticationLogable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password','role','process_id','supervisor_id','active', 'shift', 'warehouse'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pivot', 'email', 'email_verified_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'supervisor_name', 'process_name'
    ];

    public function scopeSupervisors($query) 
    {
        return $query->where('role','supervisor');
    }

    public function scopeOperators($query) 
    {
        return $query->where('role','operator');
    }

    public function scopeGuests($query) 
    {
        return $query->where('role','guest');
    }

    public function asOperatorStops() 
    {
        return $this->hasMany(Stop::class, 'operator_id')
                    ->whereDate('stop_datetime_end','=',Carbon::now()->toDateString());
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function getProcessNameAttribute()
    {
        $processName = null;
        if($this->process) {
            $processName = $this->process->description;
        }
        return $processName;
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class,'supervisor_id');
    }

    public function getSupervisorNameAttribute()
    {
        $supervisorName = null;
        if($this->supervisor) {
            $supervisorName = $this->supervisor->name;
        }
        return $supervisorName;
    }

    public function users()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function machines()
    {
        return $this->hasMany(Machine::class, 'supervisor_id');
    }

    public function dtes()
    {
        return $this->hasMany(dte::class, 'created_by');
    }

    public function getShiftHours($shift)
    {
        //$user = auth()->user();

        $hours = [];

        if($this->role == 'admin' || $this->role == 'jeferondas') {
            for ($i=0; $i < 24; $i++) { 
                $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            }
        } else {
            if ($shift == 'D') {
                for ($hour = 8; $hour <= 18; $hour++) {
                    $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                    array_push($hours, $formattedHour);
                }
            } else { // nocturno
                for ($hour = 19; $hour <= 23; $hour++) {
                    $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                    array_push($hours, $formattedHour);
                }

                for ($hour = 0; $hour <= 7; $hour++) {
                    $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                    array_push($hours, $formattedHour);
                }
            }
        }
        return $hours;
    }

    public function getShiftDescAttribute() {
        if($this->shift == 'D') {
            return 'Turno Diurno';
        } elseif($this->shift == 'N') {
            return 'Turno Nocturno';
        } else {
            return 'Jornada Completa';
        }
    }

    public function getWarehouseDescAttribute() {
        switch($this->warehouse){
            case 'AL': return 'Aluminio';
            case 'CU': return 'Cobre';
            default: return '';
        }
    }
}
