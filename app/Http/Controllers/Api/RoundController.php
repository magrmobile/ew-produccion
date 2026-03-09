<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

ini_set('memory_limit', '-1');

class RoundController extends Controller
{
    public function rounds_report()
    {
        $rounds = DB::table('rounds')
                    ->leftJoin('codes','rounds.code_id','=','codes.id')
                    ->leftJoin('machines','rounds.machine_id','=','machines.id')
                    ->leftJoin('users','rounds.user_id','=','users.id')
                    ->leftJoin('products','rounds.product_id','=','products.id')
                    ->select(
                        'rounds.id as "Id Ronda"',
                        'rounds.machine_id as "MachineId"',
                        'machines.machine_name as "Nombre Maquina"',
                        'machines.location as "Sitio de Maquina"',
                        'users.username as "Usuario Supervisor"',
                        DB::raw('CASE WHEN rounds.shift = "D" THEN "Diurno" ELSE "Nocturno" END AS "Turno"'),
                        'rounds.round_date as "Fecha"',
                        'rounds.hour as "Hora"',
                        'rounds.produced_meters as "Metros Producidos"',
                        'rounds.production_speed as "Velocidad"',
                        'rounds.product_id as "ProductId"',
                        'products.product_name as "Nombre Producto"',
                        DB::raw('CASE WHEN rounds.production_speed > 0 THEN round(((rounds.production_speed * 60)/rounds.produced_meters) * 100, 2) ELSE 0 END AS "Efectividad (%)"'),
                        'codes.code as "Codigo Paro"',
                        'codes.description as "Descripcion de Paro"',
                        'rounds.no_production_reason as "Comentario o Razon de no Produccion"'
                    )
                    ->get();
        
        return $rounds;
    }
}