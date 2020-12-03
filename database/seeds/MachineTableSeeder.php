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
        factory(Machine::class, 5)->states('aluminio')->create();

        factory(Machine::class, 5)->states('cobre')->create();
    }
}
