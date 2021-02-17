<?php

use Illuminate\Database\Seeder;
use App\Device;

class DeviceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Device::create ([ 'serial_number' => '21bc1aed9761e137', 'mac_address' => '94:BE:46:61:C7:49', 'device_name' => 'EXTAL1' ]);
        Device::create ([ 'serial_number' => 'ef24209ac29a8f82', 'mac_address' => '94:BE:46:61:C6:CF', 'device_name' => 'CABAL1' ]);
        Device::create ([ 'serial_number' => 'd7cfaa31f1ff9fa3', 'mac_address' => '94:BE:46:61:C6:BA', 'device_name' => 'CABAL2' ]);
        Device::create ([ 'serial_number' => 'c0300aa5b0beb1bf', 'mac_address' => '94:BE:46:61:C2:BA', 'device_name' => 'CABAL3' ]);
        Device::create ([ 'serial_number' => 'b274af6bfca92a7b', 'mac_address' => '94:BE:46:61:C2:B9', 'device_name' => 'CABAL4' ]);
        Device::create ([ 'serial_number' => 'f3526ff256821eb5', 'mac_address' => '94:BE:46:61:C4:30', 'device_name' => 'TREFAL1' ]);
        Device::create ([ 'serial_number' => 'c1ef074da2bf1225', 'mac_address' => '94:BE:46:61:C6:D8', 'device_name' => 'EXTCU1' ]);
        Device::create ([ 'serial_number' => '86e628e97a1291ef', 'mac_address' => '94:BE:46:61:C7:43', 'device_name' => 'EXTCU2' ]);
        Device::create ([ 'serial_number' => '1679fab0096212cf', 'mac_address' => '94:BE:46:61:C7:48', 'device_name' => 'CABCU1' ]);
        Device::create ([ 'serial_number' => 'c12868713646521e', 'mac_address' => '94:BE:46:61:C6:D5', 'device_name' => 'TREFCU1' ]);
        Device::create ([ 'serial_number' => '215c2b4aa2754890', 'mac_address' => '94:BE:46:61:C6:CC', 'device_name' => 'TREFCU2' ]);
        Device::create ([ 'serial_number' => '54058ab5d7a52fe3', 'mac_address' => '94:BE:46:61:C7:51', 'device_name' => 'CORCU1' ]);
        Device::create ([ 'serial_number' => '2a37fd34f1bdd9ec', 'mac_address' => '94:BE:46:61:C1:F8', 'device_name' => 'SUPPT1' ]);    
    }
}
