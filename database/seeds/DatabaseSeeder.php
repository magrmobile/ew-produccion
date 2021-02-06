<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            DeviceTableSeeder::class,
            MachineTableSeeder::class,
            //FamilyTableSeeder::class,
            ProductTableSeeder::class,
            ColorTableSeeder::class,
            CodeTableSeeder::class,
            ConversionTableSeeder::class
        ]);
    }
}