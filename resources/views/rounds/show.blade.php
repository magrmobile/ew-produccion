@extends('layouts.panel')

@section('styles')
<style>
    .row-title {
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="card shadow border-0">
    <div class="card-body">
        <p class="card-text">
            <div class="row">
                <div class="col-6 row-title">Maquina:</div>
                <div class="col-6">{{ $round->machine->machine_name }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Turno:</div>
                @if ($round->shift == 'D')
                <div class="col-6">Diurno</div>
                @else
                <div class="col-6">Nocturno</div>
                @endif
                <div class="w-100"></div>
                <div class="col-6 row-title">Fecha de Ronda:</div>
                <div class="col-6">{{ $round->round_date }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Hora de Ronda:</div>
                <div class="col-6">{{ $round->hour }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Metros Producidos:</div>
                <div class="col-6">{{ $round->produced_meters }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Velocidad de Produccion:</div>
                <div class="col-6">{{ $round->production_speed }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Motivo de No Produccion:</div>
                <div class="col-6">{{ $round->no_production_reason }}</div>
                <div class="w-100"></div>
            </div>
        </p>
        <a href="{{ url('/rounds') }}" class="btn btn-default btn-sm">
            Volver
        </a>
    </div>  
</div>
@endsection