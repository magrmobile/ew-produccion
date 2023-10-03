@extends('layouts.panel')

@section('styles')
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
<meta name="csrf_token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.4/fc-4.0.1/fh-3.2.1/datatables.min.css"/>
<style type="text/css">
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 360px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    #rounds-table td {
        padding: 0.5em;
        text-align: center;
    }

    #rounds-table th {
        font-size: 10pt;
        font-weight: 600;
        padding: 0.5em;
        text-align: center;
    }

    .green {
        color: green;
    }

    .red {
        color: red;
    }

    .yellow {
        color:  #f1c40f;
    }

    .up-arrow::before {
        content: "↑";
    }

    .down-arrow::before {
        content: "↓";
    }

    .dt-buttons {
        float: right;
    }
</style>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="mb-0">Informacion de Rondas</h1>
            </div>
            <div class="col text-right">
                <a href="{{ url('rounds') }}" class="btn btn-sm btn-success">
                    Ver Detalle
                </a>
                <a href="{{ url('rounds/create') }}" class="btn btn-sm btn-success">
                    Registrar Ronda
                </a>
            </div>
        </div>
    </div>
    @if(session('notification'))
    <div class="card-body">
        <div class="alert alert-success" role="alert">
            {{ session('notification') }}
        </div>
    </div>
    @endif
    <div class="card-body">
        <!--<canvas id="efficiencyChart"></canvas>-->
        <form method="GET" onchange="this.submit();" action="{{ route('rounds.dashboard') }}" >
            <div class="form-group">
                <select name="filter" class="form-control">
                    <option value="day" {{ $filter == 'day' ? 'selected' : '' }}>Día</option>
                    <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Mes</option>
                    <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Año</option>
                    <option value="dates" {{ $filter == 'dates' ? 'selected' : '' }}>Fechas</option>
                </select>
            </div>
            <div class="row" id="dates" name="dates" style="display: none;">
                <div class="col">
                    <label for="start_date" class="form-label">Fecha de Inicio:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="col">
                    <label for="end_date" class="form-label">Fecha de Fin:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
            </div>
        </form>
    </div>
    <!-- Estadisticas de Eficiencia -->
    <div class="card-body">
        <div class="row mb-4">
            <!-- Eficiencia Acumulada -->
            <div class="col-lg-3">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Eficiencia Acumulada</h5>
                                <span class="h1 font-weight-bold mb-0">{{ $controller->overallEfficiency() }}%</span>
                            </div>
                            <!--<div class="col-auto">
                                <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                                    <i class="ni ni-chart-pie-35"></i>
                                </div>
                            </div>-->
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            @if(round($controller->overallEfficiency() - 85,2) < 0)
                            <span class="text-danger mr-2">
                                <i class="fa fa-arrow-down"></i>
                                {{ round($controller->overallEfficiency() - 85,2) }}%
                            </span>
                            <span class="text-nowrap">Bajo la Meta (85%)</span>
                            @else
                            <span class="text-success mr-2">
                                <i class="fa fa-arrow-up"></i>
                                {{ round($controller->overallEfficiency() - 85,2) }}%
                            </span>
                            <span class="text-nowrap">Sobre la Meta (85%)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <!-- Eficiencia Mes Actual -->
            <div class="col-lg-3">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">       
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Eficiencia Mes Actual</h5>
                                <span class="h1 font-weight-bold mb-0">{{ $controller->monthlyEfficiency(date('m'), date('Y')) }}%</span>
                            </div>
                            <!--<div class="col-auto">
                                <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                                    <i class="ni ni-chart-pie-35"></i>
                                </div>
                            </div>-->
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            @if(round($controller->monthlyEfficiency(date('m'), date('Y')) - 85,2) < 0)
                            <span class="text-danger mr-2">
                                <i class="fa fa-arrow-down"></i>
                                {{ round($controller->monthlyEfficiency(date('m'), date('Y')) - 85,2) }}%
                            </span>
                            <span class="text-nowrap">Bajo la Meta (85%)</span>
                            @else
                            <span class="text-success mr-2">
                                <i class="fa fa-arrow-up"></i>
                                {{ round($controller->monthlyEfficiency(date('m'), date('Y')) - 85,2) }}%
                            </span>
                            <span class="text-nowrap">Sobre la Meta (85%)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <!-- Diferencia Mes Anterior -->
            <div class="col-lg-3">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">       
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Diferencia Mes Anterior</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $controller->efficiencyChange(date('m'), date('Y')) }}%</span>
                            </div>
                            <!--<div class="col-auto">
                                <div class="icon icon-shape bg-red text-white rounded-circle shadow">
                                    <i class="ni ni-chart-pie-35"></i>
                                </div>
                            </div>-->
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-success mr-2"></span>
                            <span class="text-nowrap"></span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Rondas Pendientes -->
            <!-- Diferencia Mes Anterior -->
            <div class="col-lg-3">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">       
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Rondas Pendientes</h5>
                                <span class="h2 font-weight-bold mb-0"><a href="{{ route('rounds.missing') }}">{{ $missingRounds->count() }}</a></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-red text-white rounded-circle shadow">
                                    <i class="ni ni-bell-55"></i>
                                    
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-success mr-2"></span>
                            <span class="text-nowrap"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Grafico -->
    <div class="card-body">
        <figure class="highcharts-figure">
            <div id="container"></div>
        </figure>
    </div>

    <!-- Tablas y Reportes -->
    
    
    <div class="card-body">
        @php
            $totals = [];
            $hour_now = date("H");

            if($hour_now >= 8 && $hour_now <= 18) {
                $shift = 'D';
            } else {
                $shift = 'N';
            }
        @endphp

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Resultados de Eficiencia de Rondas</a>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profile</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <table id="rounds-table" name="rounds-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            @foreach($user->getShiftHours($shift) as $hour)
                            <th>{{ $hour }}</th>
                            @endforeach
                            <th style="vertical-align:center">Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Meta</th>
                            @foreach($user->getShiftHours($shift) as $hour)
                            <th>85%</th>
                            @endforeach
                            <th>85%</th>
                        </tr>
                        @foreach($machines as $machine)
                        <tr>
                            <th>{{ $machine->machine_name }}</th>
                            @foreach($user->getShiftHours($shift) as $hour)
                            @php
                                $round = $rounds->where('machine_id', $machine->id)->where('hour', $hour.':00');

                                $average = $round->count() > 0 ? $round->sum('percentage_achievement') / $round->count() : 0;

                                if(!isset($totals[$hour])) {
                                    if($round->count() > 0) {
                                        $totals[$hour] = ['sum' => $average, 'count' => 1];
                                    }
                                } else {
                                    if($round->count() > 0) {
                                        $totals[$hour]['sum'] += $average;
                                        $totals[$hour]['count'] += 1;
                                    }
                                }

                                if($average <= 65) {
                                    $color = 'red';
                                    $arrow = 'down-arrow';
                                } elseif ($average >=66 && $average <= 85) {
                                    $color = 'yellow';
                                    $arrow = 'down-arrow';
                                } else {
                                    $color = 'green';
                                    $arrow = 'up-arrow';
                                }
                            @endphp
                            <td class="{{ $color }}">
                                <span class="{{ $arrow }}"></span>
                                {{ round($average) }}%
                            </td>
                            @endforeach
                            @php
                                $overall_row_rounds = $rounds->where('machine_id', $machine->id);
                                $overall_row_average = $overall_row_rounds->count() > 0 ? $overall_row_rounds->sum('percentage_achievement') / $overall_row_rounds->count() : 0;
                                
                                if($overall_row_average <= 65) {
                                    $color = 'red';
                                    $arrow = 'down-arrow';
                                } elseif ($overall_row_average >=66 && $overall_row_average <= 85) {
                                    $color = 'yellow';
                                    $arrow = 'down-arrow';
                                } else {
                                    $color = 'green';
                                    $arrow = 'up-arrow';
                                }
                            @endphp
                            <th class="{{ $color }}">
                                <span class="{{ $arrow }}"></span>
                                {{ round($overall_row_average, 1)  }}%
                            </th>
                        </tr>
                        @endforeach
                        <tr>
                            <th>PROMEDIO</th>
                            @php
                                $totalSum = 0;
                                $totalHours = 0;
                            @endphp
                            @foreach($user->getShiftHours($shift) as $hour)
                                @php             
                                    $round = $rounds->where('hour', $hour.':00');

                                    $average = $round->count() > 0 ? $round->sum('percentage_achievement') / $round->count() : 0;

                                    if(!isset($totals[$hour])) {
                                        if($round->count() > 0) {
                                            $totals[$hour] = ['sum' => $average, 'count' => 1];
                                        }
                                    } else {
                                        if($round->count() > 0) {
                                            $totals[$hour]['sum'] += $average;
                                            $totals[$hour]['count'] += 1;
                                        }
                                    }

                                    if($average <= 65) {
                                        $color = 'red';
                                        $arrow = 'down-arrow';
                                    } elseif ($average >=66 && $average <= 85) {
                                        $color = 'yellow';
                                        $arrow = 'down-arrow';
                                    } else {
                                        $color = 'green';
                                        $arrow = 'up-arrow';
                                    }
                                    
                                    $totalSum += $average;
                                    $totalHours++;
                                @endphp
                                <th class="{{ $color }}">
                                    <span class="{{ $arrow }}"></span>
                                    {{ round($average, 1) }}%
                                </th>
                            @endforeach
                            @php
                                $average = $totalSum / $totalHours;

                                if($average <= 65) {
                                    $color = 'red';
                                    $arrow = 'down-arrow';
                                } elseif ($average >=66 && $average <= 85) {
                                    $color = 'yellow';
                                    $arrow = 'down-arrow';
                                } else {
                                    $color = 'green';
                                    $arrow = 'up-arrow';
                                }
                                //$average = 0;
                            @endphp
                            <th class="{{ $color }}">
                                <span class="{{ $arrow }}"></span>
                                {{ round($average, 1) }}%
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>


<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/v/bs4/dt-1.11.4/fc-4.0.1/fh-3.2.1/datatables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script>
// Highcharts
Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Eficiencia {{ $user->warehouse_desc }} - {{ $user->shift_desc }}' 
    },
    xAxis: {
        categories: @json(array_keys($hourlyEfficiencies))
    },
    yAxis: {
        title: {
            text: 'Eficiencia (%)'
        }
    },
    tooltip: {
        valueSuffix:'%'
    },
    plotOptions: {
        spline: {
            lineWidth: 3,
            states: {
                hover: {
                    lineWidth: 4
                }
            },
            marker: {
                enabled: false
            }
        }
    },
    series: [{
        name: 'Eficiencia',
        data: @json(array_values($hourlyEfficiencies)),
        color: '{{ $average >= 85 ? 'green' : 'red' }}'
    }, {
        name: 'Meta',
        data: Array(@json(count($hourlyEfficiencies))).fill(85),
        color: 'green'
    }],
    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }
});

$(document).ready( function () {
    // Disable search and ordering by default
    $.extend( $.fn.dataTable.defaults, {
        searching: false,
        ordering:  false
    } );

    $('#rounds-table').DataTable({
        dom: 'frtipB',
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'csv',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'excel',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'pdf',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'print',
                className: 'btn btn-primary btn-sm'
            }
        ],
        responsive: true,
        paging: false,
        searchig: false,
        info: false,
        autoWidth: true,
    });

    const filterSelect = document.querySelector('select[name="filter"]');
    const datesDiv = document.getElementById('dates');
    const startDateSelect = document.querySelector('select[name="start_date"]');
    const endDateSelect = document.querySelector('select[name="end_date"]');
    const form = document.querySelector('form');

    filterSelect.addEventListener('change', function() {
        const selectedValue = filterSelect.value;

        if(selectedValue === 'dates') {
            datesDiv.style.display = 'block';
        } else {
            datesDiv.style.display = 'none';
            form.submit();
        }
    });


} );
</script>

@endsection