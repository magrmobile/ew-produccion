<?php

namespace App\Exports;

use App\Stop;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StopsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        return view('exports.stops', [
            'stops' => Stop::all()
        ]);
    }
}
