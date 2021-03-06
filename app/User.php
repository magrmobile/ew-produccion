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
        'name', 'username', 'email', 'password','role','process_id','supervisor_id'
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

    public function supervisor()
    {
        return $this->belongsTo(User::class,'supervisor_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }
}
