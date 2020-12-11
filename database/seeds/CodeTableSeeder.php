<?php

use Illuminate\Database\Seeder;
use App\Code;

class CodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Code::create ([ 'code' => 0, 'description' => 'Máquina trabajando normal', 'type' => 'Programado', ]);
        Code::create ([ 'code' => 1, 'description' => 'Preparación de máquina', 'type' => 'Programado', ]);
        Code::create ([ 'code' => 2, 'description' => 'Cambio de carrete', 'type' => 'Programado', ]);
        Code::create ([ 'code' => 3, 'description' => 'Comida', 'type' => 'Programado', ]);
        Code::create ([ 'code' => 4, 'description' => 'Falta de pedidos', 'type' => 'Programado', ]);
        Code::create ([ 'code' => 5, 'description' => 'Mantenimiento preventivo', 'type' => 'Programado', ]);
        Code::create ([ 'code' => 6, 'description' => 'Mantenimiento correctivo', 'type' => 'No programado', ]);
        Code::create ([ 'code' => 7, 'description' => 'Fallo en MP/producto', 'type' => 'No programado', ]);
        Code::create ([ 'code' => 8, 'description' => 'Fallo en equipo auxiliar', 'type' => 'No programado', ]);
        Code::create ([ 'code' => 9, 'description' => 'Falta de recursos', 'type' => 'No programado', ]);
        Code::create ([ 'code' => 10, 'description' => 'Falta personal', 'type' => 'No programado', ]);
        Code::create ([ 'code' => 11, 'description' => 'Otros', 'type' => 'No programado', ]);

        //factory(Code::class, 10)->create();
    }
}
