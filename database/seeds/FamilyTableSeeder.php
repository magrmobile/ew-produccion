<?php

use Illuminate\Database\Seeder;
use App\Family;

class FamilyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Family::class, 17)->create();
    }
}
