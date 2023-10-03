<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Machine;
use App\Product;

class Speed extends Model
{
    protected $table = 'machine_product';

    protected $fillable = [
        'machine_id',
        'product_id',
        'speed',
        'created_by'
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
        return $this->belongsTo(User::class, 'created_by');
    }
}
