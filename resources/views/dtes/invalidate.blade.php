@extends('layouts.panel')

@section('content')
<style>
    .swal-wide {
        width: 1000px !important;
    }
</style>
<div class="card shadow">
    <div class="card-header border-1">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Anulacion de Documento (DTE: {{ $dte->codigoGeneracion }})</h3>
                <h4 class="mb-0">Cliente: {{ $dte->customer->nombre }}</h4>
                <h4 class="mb-0">Tipo Documento: {{ $dte->tipoDocumento()->valor }}</h4>
            </div>
        </div>
    </div>
    @if(session('message'))
    <div class="card-body">
        <div class="alert alert-success" role="success">
            {{ session('message') }}
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="card-body">
        <div class="alert alert-danger" role="alert">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if(session('rejectedError'))
    <div class="card-body">
        <div class="alert alert-danger" role="alert">
            {{ session('rejectedError') }}
        </div>
    </div>
    @endif
    @if(session('otherError'))
    <div class="card-body">
        <div class="alert alert-danger" role="alert">
            {{ session('otherError') }}
        </div>
    </div>
    @endif
    <div class="card-body">
        <form name="anulardte" id="anulardte" action="{{ route('dtes.invalidateDte', $dte)}}" method="POST">
            @csrf
            <div class="row">
                <div class="col">
                    <label for="type">Tipo de Anulacion:</label>
                    <select class="form-control" id="tipoAnulacion" name="tipoAnulacion" required>
                        <option value="">Seleccionar el tipo de Invalidacion</option>
                        @foreach ($tiposAnulacion as $tipoAnulacion)
                        <option value="{{ $tipoAnulacion->id }}">{{ $tipoAnulacion->valor }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col">
                    <label for="motivoAnulacion">Motivo de Anulacion:</label>
                    <input type="text" name="motivoAnulacion" id="motivoAnulacion" class="form-control" required></textarea>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div id="additionalFields" style="display: none;">
                <div class="row">
                    <div class="col">
                        <label for="codigoGeneracionR">Codigo Generacion que Reemplaza</label>
                        <input type="text" id="codigoGeneracion" name="codigoGeneracion" class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col">
                    <button class="btn btn-primary submit" type="submit">
                        <span class="btn-txt">Anular DTE</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true">
                            <i class="fas fa-spinner fa-pulse"></i>
                        </span>
                    </button>
                    <a href="{{ route('dtes.index', ['customer_id' => $dte->customer_id]) }}" class="btn btn-primary">
                        Regresar
                    </a>
                </div>
            </div>
        </form>
    </div> 
</div>
@endsection

@section('scripts')
<script>
    const typeSelect = document.getElementById('tipoAnulacion');
    const additionalFieldsDiv = document.getElementById('additionalFields');

    typeSelect.addEventListener('change', function() {
        if(typeSelect.value == 1 || typeSelect.value == 3) {
            additionalFieldsDiv.style.display = 'block';
        } else {    
            additionalFieldsDiv.style.display = 'none';
        }
    });

    $(document).ready(function() {
        $("#anulardte").submit(function() {
            $(".spinner-border").removeClass("d-none");
            $(".submit").attr("disabled", true);
            $(".btn-txt").text('Procesando...');
        });
    });
</script>
@endsection