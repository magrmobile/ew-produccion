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
        Detalle Documento Tributario Elecronico - {{ $dte->numeroControl }}
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="numeroControl">Numero de Control</label>
            <input type="text" id="numeroControl" name="numeroControl" class="form-control" readonly value="{{ $dte->numeroControl }}">
        </div>
        <div class="mb-3">
            <label for="codigoGeneracion">Codigo de Generacion</label>
            <input type="text" id="codigoGeneracion" name="codigoGeneracion" class="form-control" readonly value="{{ $dte->codigoGeneracion }}">
        </div>
        <div class="mb-3">
            <label for="customerName">Nombre de Cliente</label>
            <input type="text" id="customerName" name="customerName" class="form-control" readonly value="{{ $dte->customer->nombre }}">
        </div>
        <div class="mb-3">
            <label for="createdBy">Creado Por</label>
            <input type="text" id="createdBy" name="createdBy" class="form-control" readonly value="{{ $dte->user->username }}">
        </div>
        <div class="mb-3">
            <label for="created_at">Fecha y Hora de Creacion</label>
            <input type="text" id="created_at" name="created_at" class="form-control" readonly value="{{ $dte->created_at }}">
        </div>
        <div class="mb-3">
            <div class="row">
                <div class="col-1">
                    <label for="signed">Firmado?</label>
                </div>
                <div class="col">
                    @if($dte->signed == 1)
                        <span class="badge badge-pill badge-success">Firmado</span>
                        Fecha y Hora de Firma: {{ $dte->signed_date }}
                    @else
                        <span class="badge badge-pill badge-lg badge-danger">Sin Firmar</span>
                    @endif
                </div>
                <div class="col-1">
                    <label for="receive">Transmitido?</label>
                </div>
                <div class="col">
                    @if($dte->signed == 1)
                        <span class="badge badge-pill badge-success">Transmitido</span>
                        Fecha y Hora de Recepcion: {{ $dte->received_date }}
                    @else
                        <span class="badge badge-pill badge-lg badge-danger">Sin Transmitir</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="json_dte">Contenido JSON</label>
            <textarea name="json_dte" id="json_dte" class="form-control" readonly cols="30" rows="10">
                {{ $dte->json_dte }}
            </textarea>
        </div>
        <a href="{{ url('/dtes?customer_id=').$dte->customer_id }}" class="btn btn-default">
            Volver
        </a>
    </div>  
</div>
@endsection