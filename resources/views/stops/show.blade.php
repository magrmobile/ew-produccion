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
    <div class="card-header row-title" style="background-color:{{ isset($stop->color->hex_code) ? $stop->color->hex_code : '#ffffff' }}; color:black;">
        Paro #{{ $stop -> id }}
    </div>
    <div class="card-body">
        <p class="card-text">
            <div class="row">
                <div class="col-6 row-title">Inicio Paro:</div>
                <div class="col-6">{{ $stop->getStopDateTimeStart12Attribute() }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Fin Paro:</div>
                <div class="col-6">{{ $stop->getStopDateTimeEnd12Attribute() }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Duracion de Paro:</div>
                <div class="col-6">{{ $duration }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Operador:</div>
                <div class="col-6">{{ $stop->operator->name }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Estado:</div>
                <div class="col-6">
                    @if ($stop->stop_datetime_end != null)
                        <span class="badge badge-pill badge-success">Finalizada</span>
                    @else
                        <span class="badge badge-pill badge-info">Activa</span>
                    @endif
                </div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Machine:</div>
                <div class="col-6">{{ $stop->machine->machine_name }}</div>
                <div class="w-100"></div>
                @if(isset($stop->product->product_name))
                <div class="col-6 row-title">Product:</div>
                <div class="col-6">{{ $stop->product->product_name }}</div>
                <div class="w-100"></div>
                @endif
                @if(isset($stop->color->name))
                <div class="col-6 row-title">Color:</div>
                <div class="col-6 row-title" style="color:{{ $stop->color->hex_code }};">    
                    <i class="fas fa-circle"></i>
                    {{ $stop->color->name }}
                </div>
                <div class="w-100"></div>
                @endif
                <div class="col-6 row-title">Motivo de Paro:</div>
                <div class="col-6">{{ $stop->code->description }}</div>
                <div class="w-100"></div>
                <div class="col-6 row-title">Tipo de Paro:</div>
                <div class="col-6">{{ $stop->code->type }}</div>
                <div class="w-100"></div>
                @if(isset($stop->meters))
                <div class="col-6 row-title">Metros Producidos:</div>
                <div class="col-6">{{ $stop->meters.' Mts.' }}</div>
                <div class="w-100"></div>
                @endif
                @if(isset($stop->comment))
                <div class="col-6 row-title">Observaciones:</div>
                <div class="col-6">{{ $stop->comment }}</div>
                <div class="w-100"></div>
                @endif
            </div>
        </p>
        <a href="{{ url('/stops') }}" class="btn btn-default">
            Volver
        </a>
    </div>  
</div>
@endsection