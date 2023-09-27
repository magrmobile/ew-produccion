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
        Machine::create ([ 'machine_name' => 'Cab Disco 400 #1', 'process_id' => 1, 'warehouse' => 'AL', 'device_id' => 4 ]);
        Machine::create ([ 'machine_name' => 'Cab Disco 400 #2', 'process_id' => 1, 'warehouse' => 'AL', 'device_id' => 4 ]);
        Machine::create ([ 'machine_name' => 'Cableadora #3', 'process_id' => 1, 'warehouse' => 'AL', 'device_id' => 3 ]);
        Machine::create ([ 'machine_name' => 'Cableadora #4', 'process_id' => 1, 'warehouse' => 'AL', 'device_id' => 5 ]);
        Machine::create ([ 'machine_name' => 'Cableadora #5', 'process_id' => 1, 'warehouse' => 'AL', 'device_id' => 3 ]);
        Machine::create ([ 'machine_name' => 'JSE 630', 'process_id' => 1, 'warehouse' => 'CU', 'device_id' => 9 ]);
        Machine::create ([ 'machine_name' => 'Larebee #1', 'process_id' => 3, 'warehouse' => 'CU', 'device_id' => 11 ]);
        Machine::create ([ 'machine_name' => 'Larebee #2', 'process_id' => 3, 'warehouse' => 'AL', 'device_id' => 6 ]);
        Machine::create ([ 'machine_name' => 'Lesmo 760', 'process_id' => 1, 'warehouse' => 'CU', 'device_id' => 9 ]);
        Machine::create ([ 'machine_name' => 'Lesmo 800', 'process_id' => 1, 'warehouse' => 'CU', 'device_id' => 9 ]);
        Machine::create ([ 'machine_name' => 'Línea Copperweld #1', 'process_id' => 2, 'warehouse' => 'CU', 'device_id' => 8 ]);
        Machine::create ([ 'machine_name' => 'Línea THHN #1', 'process_id' => 2, 'warehouse' => 'CU', 'device_id' => 7 ]);
        Machine::create ([ 'machine_name' => 'Línea THHN #2', 'process_id' => 2, 'warehouse' => 'CU', 'device_id' => 7 ]);
        Machine::create ([ 'machine_name' => 'Línea THHN #3', 'process_id' => 2, 'warehouse' => 'CU', 'device_id' => 8 ]);
        Machine::create ([ 'machine_name' => 'Línea WP #1', 'process_id' => 2, 'warehouse' => 'AL', 'device_id' => 1 ]);
        Machine::create ([ 'machine_name' => 'Línea WP #2', 'process_id' => 2, 'warehouse' => 'AL', 'device_id' => 1 ]);
        Machine::create ([ 'machine_name' => 'Multifilar 4 Hilos', 'process_id' => 3, 'warehouse' => 'CU', 'device_id' => 10 ]);
        Machine::create ([ 'machine_name' => 'Niehoff #3', 'process_id' => 3, 'warehouse' => 'CU', 'device_id' => 11 ]);
        Machine::create ([ 'machine_name' => 'RBD #1', 'process_id' => 3, 'warehouse' => 'CU', 'device_id' => 10 ]);
        Machine::create ([ 'machine_name' => 'RBD #2', 'process_id' => 3, 'warehouse' => 'AL', 'device_id' => 6 ]);
        Machine::create ([ 'machine_name' => 'SMD', 'process_id' => 1, 'warehouse' => 'AL', 'device_id' => 2 ]);
        Machine::create ([ 'machine_name' => 'Suzhou', 'process_id' => 3, 'warehouse' => 'CU', 'device_id' => 10 ]);
        Machine::create ([ 'machine_name' => 'VILA', 'process_id' => 1, 'warehouse' => 'AL', 'device_id' => 2 ]);
        Machine::create ([ 'machine_name' => 'Ya-sih #1', 'process_id' => 4, 'warehouse' => 'CU', 'device_id' => 12 ]);
        Machine::create ([ 'machine_name' => 'Ya-sih #2', 'process_id' => 4, 'warehouse' => 'CU', 'device_id' => 12 ]);

        //factory(Machine::class, 5)->states('aluminio')->create();
        //factory(Machine::class, 5)->states('cobre')->create();
    }
}
