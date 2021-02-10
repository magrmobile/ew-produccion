<?php

use Illuminate\Database\Seeder;
use App\Process;

class ProcessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Process::create([ 'description' => 'Cableado']);
        Process::create([ 'description' => 'Extrusion']);
        Process::create([ 'description' => 'Trifilado']);
        Process::create([ 'description' => 'Fraccionado']);
    }
}
