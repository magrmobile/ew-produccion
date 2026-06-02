<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\User;
use Illuminate\Support\Facades\DB;

class dte extends Model
{
    protected $fillable = [
        'customer_id', 
        'numeroControl', 
        'codigoGeneracion', 
        'json_dte', 
        'created_by', 
        'file_csv',
        'signed',
        'signed_date',
        'signed_by',
        'sign',
        'received',
        'received_date',
        'received_by',
        'stamp',
        'invalidate',
        'invalidate_stamp',
        'invalidate_by',
        'invalidate_date',
        'tipoDte',
        'provider',
        'emisor_nit'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function signed_user()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function received_user()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function invalidate_user()
    {
        return $this->belongsTo(User::class, 'invalidate_by');
    }

    public function tipoDocumento()
    {
        return DB::table('cat002')
            ->select('valor')
            ->where('id', $this->tipoDte)
            ->first();
    }
}
