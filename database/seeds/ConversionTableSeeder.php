<?php

use Illuminate\Database\Seeder;
use App\Conversion;

class ConversionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conversion::create ([ 'description' => 'Cajas [100 m]', 'factor' => '100', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Cajas [250 pies]', 'factor' => '76.2', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Cajas [500 pies]', 'factor' => '152.4', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Rollos [500 pies]', 'factor' => '152.4', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Rollos [100 m]', 'factor' => '100', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Rollos [125 m]', 'factor' => '125', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Rollos [200 m]', 'factor' => '200', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Rollos [250 pies]', 'factor' => '76.2', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Rollos [500 m]', 'factor' => '500', 'type' => 'R', ]);
        Conversion::create ([ 'description' => 'Carrete Metal/Madera', 'factor' => '1', 'type' => 'C', ]);
    }
}
