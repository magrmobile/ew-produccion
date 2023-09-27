<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\User;

class dte extends Model
{
    protected $fillable = [
        'customer_id', 'numeroControl', 'codigoGeneracion', 'json_dte', 'created_by', 'file_csv'
    ];

    public function customer() 
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
