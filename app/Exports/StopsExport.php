<?php

namespace App\Exports;

use App\Stop;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Illuminate\Support\Facades\DB;

class StopsExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        return view('exports.stops', [
            //'stops' => Stop::all()
            'stops' => DB::table('stops')
                    ->leftJoin('codes','stops.code_id','=','codes.id')
                    ->leftJoin('machines','stops.machine_id','=','machines.id')
                    ->leftJoin('users','stops.operator_id','=','users.id')
                    ->leftJoin('products','stops.product_id','=','products.id')
                    ->leftJoin('colors','stops.color_id','=','colors.id')
                    ->leftJoin('processes','machines.process_id','=','processes.id')
                    ->leftJoin('conversions','stops.conversion_id','=','conversions.id')
                    ->select(
                        DB::raw('YEAR(stops.stop_datetime_end) as year'),
                        DB::raw('MONTH(stops.stop_datetime_end) as month'),
                        DB::raw('WEEK(stops.stop_datetime_end) as week'),
                        DB::raw('DATE_FORMAT(stops.stop_datetime_end,"%d/%m/%Y") as date'),
                        DB::raw('DAYNAME(stops.stop_datetime_end) as dayname_end'),
                        DB::raw('CASE WHEN HOUR(stops.stop_datetime_end) >= 7 AND HOUR(stops.stop_datetime_end) <= 17 THEN "D" ELSE "N" END as schedule'),
                        'processes.description as process',
                        'machines.machine_name',
                        'machines.warehouse',
                        'users.name as operator_name',
                        'products.product_name',
                        'colors.name as color_name',
                        'codes.code as stop_code',
                        'codes.description as code_description',
                        'codes.type as stop_type',
                        'conversions.description as conversion_description',
                        DB::raw('DATE_FORMAT(stops.stop_datetime_start,"%H:%i:%s") as stop_start'),
                        DB::raw('DATE_FORMAT(stops.stop_datetime_end,"%H:%i:%s") as stop_end'),
                        DB::raw('TIMEDIFF(stops.stop_datetime_end,stops.stop_datetime_start) as duration'),
                        'stops.quantity',
                        'stops.meters',
                        'stops.comment'
                    )
                    ->get()
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
}
