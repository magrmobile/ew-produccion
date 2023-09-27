<?php

use Illuminate\Database\Seeder;
use App\Round;

class RoundsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Round::factory(50)->create();
    }

}
