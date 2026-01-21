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
        <h4>{{ $dte->tipoDocumento()->valor }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">Numero de Control:</div>
            <div class="col"><h3>{{ $dte->numeroControl }}</h3></div>
        </div>
        <div class="row">
            <div class="col">Codigo de Generacion:</div>
            <div class="col"><h3>{{ $dte->codigoGeneracion }}</h3></div>
        </div>
        <div class="row">
            <div class="col">Nombre de Cliente:</div>
            <div class="col"><h3>{{ $dte->customer->nombre }}</h3></div>
        </div>
        <div class="row">
            <div class="col">Creado Por:</div>
            <div class="col"><h3>{{ $dte->created_user->name }}</h3></div>
        </div>
        <div class="row">
            <div class="col">Fecha y Hora de Creacion</div>
            <div class="col"><h3>{{ $dte->created_at }}</h3></div>
        </div>
        <div class="row">
            <div class="col">Nombre de Archivo CSV</div>
            <div class="col"><h3>{{ $dte->file_csv }}</h3></div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="mb-r">
            <div class="table-responsive">
                <table class="table align-items-center">
                    <thead class="thead-light">
                        <th scope="col">Estado</th>
                        <th scope="col">Fecha y Hora</th>
                        <th scope="col">Usuario</th>
                    </thead>
                    <tbody class="list">
                        @if($dte->signed == 1)
                            <tr>
                                <td><span class="badge badge-pill badge-lg badge-success">Firmado</span></td>
                                <td>{{ $dte->signed_date }}</td>
                                <td>{{ $dte->signed_user->name }}</td>
                            </tr>
                        @endif
                        @if($dte->received == 1)
                            <tr>
                                <td><span class="badge badge-pill badge-lg badge-success">Transmitido</span></td>
                                <td>{{ $dte->received_date }}</td>
                                <td>{{ $dte->received_user->name }}</td>
                            </tr>
                        @endif
                        @if($dte->invalidate == 1)
                            <tr>
                                <td><span class="badge badge-pill badge-lg badge-danger">Anulado</span></td>
                                <td>{{ $dte->invalidate_date }}</td>
                                <td>{{ $dte->invalidate_user->name }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
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
        <!--<a href="{{ asset('public/storage/app/json/'.$dte->codigoGeneracion.'.json') }}" class="btn btn-default">
            Descargar JSON
        </a>-->
    </div>  
</div>
@endsection