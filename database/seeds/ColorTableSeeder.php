<?php

use Illuminate\Database\Seeder;
use App\Color;

class ColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::create ([ 'name' => 'R', 'hex_code' => '#FF0000' ]);
        Color::create ([ 'name' => 'N', 'hex_code' => '#000000' ]);
        Color::create ([ 'name' => 'BLC', 'hex_code' => '#FFFFFF' ]);
        Color::create ([ 'name' => 'AM', 'hex_code' => '#FFFF00' ]);
        Color::create ([ 'name' => 'AZ', 'hex_code' => '#0000FF' ]);
        Color::create ([ 'name' => 'V', 'hex_code' => '#00FF00' ]);
        Color::create ([ 'name' => 'GRIS', 'hex_code' => '#808080' ]);
        Color::create ([ 'name' => 'NRJ', 'hex_code' => '#FF4500' ]);
        Color::create ([ 'name' => 'CAFE', 'hex_code' => '#8B4513' ]);
        Color::create ([ 'name' => 'X', 'hex_code' => '#00FFFF' ]);
        Color::create ([ 'name' => 'OTRO', 'hex_code' => '#FF00FF' ]);
        //factory(Color::class, 5)->create();
    }
}
