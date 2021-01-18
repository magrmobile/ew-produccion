@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Paro #{{ $stop -> id }}</h3>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <tbody>
            <tr>
                <th>Fecha Inicio Paro:</th><td>{{ $stop->stop_date_start }}</td>
            </tr>
            <tr>
                <th>Hora Inicio Paro:</th><td>{{ $stop->stop_time_start  }}</td>
            </tr>
            <tr>
                <th>Fecha Fin Paro:</th><td>{{ $stop->stop_date_end }}</td>
            </tr>
            <tr>
                <th>Hora Fin Paro:</th><td>{{ $stop->stop_time_end  }}</td>
            </tr>
            <tr>
                <th>Duracion de Paro:</th><td>{{ $duration  }}</td>
            </tr>
            <tr>
                <th>Operador:</th><td>{{ $stop->operator->name  }}</td>
            </tr>
            <tr>
                <th>Estado:</th> 
                @if ($stop->stop_date_end != null)
                    <td><span class="badge badge-pill badge-success">Finalizada</span></td>
                @else
                    <td><span class="badge badge-pill badge-info">Activa</span></td>
                @endif
            </tr>
            <tr>
                <th>Maquina:</th><td>{{ $stop->machine->machine_name  }}</td>
            </tr>
            <tr>
                <th>Producto:</th><td>{{ isset($stop->product->product_name) ? $stop->product->product_name : ''  }}</td>
            </tr>
            <tr>
                <th>Color:</th><td style="background: {{ isset($stop->color->hex_code) ? $stop->color->hex_code : '' }}"> {{ isset($stop->color->name) ? $stop->color->name : ''  }} </td>
            </tr>
            <tr>
                <th>Motivo de Paro:</th><td>{{ $stop->code->description  }}</td>
            </tr>
            <tr>
                <th>Tipo de Paro:</th><td>{{ $stop->code->type  }}</td>
            </tr>
            <tr>
                <th>Metros Producidos:</th><td>{{ isset($stop->meters) ? $stop->meters.' Mts.' : ''  }}</td>
            </tr>
            <tr>
                <th>Observaciones:</th><td>{{ $stop->comment  }}</td>
            </tr>
            </tbody>
        </table>

        <a href="{{ url('/stops') }}" class="btn btn-default">
            Volver
        </a>
    </div>
</div>
@endsection