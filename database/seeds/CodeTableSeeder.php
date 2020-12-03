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
        factory(Code::class, 10)->create();
    }
}
