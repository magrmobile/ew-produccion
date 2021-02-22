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
        Family::create ([ 'family_name' => 'AAC' ]);
        Family::create ([ 'family_name' => 'ACSR' ]);
        Family::create ([ 'family_name' => 'Cable de Control' ]);
        Family::create ([ 'family_name' => 'Cable de Potencia' ]);
        Family::create ([ 'family_name' => 'CCS' ]);
        Family::create ([ 'family_name' => 'DW' ]);
        Family::create ([ 'family_name' => 'FN' ]);
        Family::create ([ 'family_name' => 'H07V' ]);
        Family::create ([ 'family_name' => 'Multiplex' ]);
        Family::create ([ 'family_name' => 'NM-B' ]);
        Family::create ([ 'family_name' => 'PAWC' ]);
        Family::create ([ 'family_name' => 'PV 2000V' ]);
        Family::create ([ 'family_name' => 'PV 600V' ]);
        Family::create ([ 'family_name' => 'RHH 2000 V' ]);
        Family::create ([ 'family_name' => 'RHH 600V' ]);
        Family::create ([ 'family_name' => 'SAE' ]);
        Family::create ([ 'family_name' => 'SE-U' ]);
        Family::create ([ 'family_name' => 'SPT' ]);
        Family::create ([ 'family_name' => 'Telescopico' ]);
        Family::create ([ 'family_name' => 'TF' ]);
        Family::create ([ 'family_name' => 'TFF' ]);
        Family::create ([ 'family_name' => 'TFFN' ]);
        Family::create ([ 'family_name' => 'TFN' ]);
        Family::create ([ 'family_name' => 'TGP' ]);
        Family::create ([ 'family_name' => 'THHN' ]);
        Family::create ([ 'family_name' => 'THHW' ]);
        Family::create ([ 'family_name' => 'THHW LS' ]);
        Family::create ([ 'family_name' => 'TSJ' ]);
        Family::create ([ 'family_name' => 'USE' ]);
        Family::create ([ 'family_name' => 'WP' ]);
        Family::create ([ 'family_name' => 'WP-ACSR' ]);
        Family::create ([ 'family_name' => 'XFLEX' ]);
        Family::create ([ 'family_name' => 'XHHW' ]);
        Family::create ([ 'family_name' => 'X-WP' ]);
        Family::create ([ 'family_name' => 'X-WP-ACSR' ]);
    }
}
