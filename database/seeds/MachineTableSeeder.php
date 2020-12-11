<?php

use Illuminate\Database\Seeder;
use App\Machine;

class MachineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Machine::create ([ 'machine_name' => 'Cab Disco #4', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cab Disco 400 #1', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cab Disco 400 #2', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cab Disco 500', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cab Disco 500 #1', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Cab Disco 500 #2', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Cableadora #1', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cableadora #2', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cableadora #3', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cableadora #4', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cableadora #5', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'JSE 630', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Larebee #1', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Larebee #2', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Lesmo 760', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Lesmo 800', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Línea THHN #1', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Línea THHN #2', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Linea THHN #3', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Línea Copperweld #1', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Línea WP #1', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Línea WP #2', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Multifilar 4 hilos', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Niehoff #1', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Niehoff #3', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'RBD #1', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'RBD #2', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'SMD', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Suzhou', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'VILA', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Ya-sih #1', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Ya-sih #2', 'warehouse' => 'CU', ]);
        Machine::create ([ 'machine_name' => 'Cab Disco #1', 'warehouse' => 'AL', ]);
        Machine::create ([ 'machine_name' => 'Cab Disco #2', 'warehouse' => 'AL', ]);

        //factory(Machine::class, 5)->states('aluminio')->create();
        //factory(Machine::class, 5)->states('cobre')->create();
    }
}
