@extends('layouts.panel')

@section('content')
<style>
    input, input:read-only {
        color: black;
        font-size: 16pt;
        font-weight: bold;
    }

    .swal-wide {
        width: 1000px !important;
    }
</style>
<div class="card shadow">
    <div class="card-header border-1">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Generar Facturacion (JSON)</h3>
            </div>
        </div>
    </div>
    @if(session('notification'))
    <div class="card-body">
        <div class="alert alert-danger" role="alert">
            {{ session('notification') }}
        </div>
    </div>
    @endif
    @if(session('confirm'))
    <div class="card-body">
        <div class="alert alert-success" role="success">
            {{ session('confirm') }}
            <a href="{{ route('dtes.sendDte', ['id' => session('dteId') ])}}" id="sendBtn" name="sendBtn" class="btn btn-primary submit-send">
                <span class="btn-txt-send">Enviar</span>
                <span class="spinner-border-send spinner-border-sm d-none" role="status" aria-hidden="true">
                    <i class="fas fa-spinner fa-pulse"></i>
                </span>
            </a>
        </div>
    </div>
    @endif
    @if(session('message'))
    <div class="card-body">
        <div class="alert alert-success" role="success">
            {{ session('message') }}
        </div>
    </div>
    @endif
    @if(session('errors'))
    <div class="card-body">
        <div class="alert alert-danger" role="alert">
            <ul>
            @foreach($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    </div>
    @endif
    <div class="card-body">
        <form name="uploadform" id="uploadform" action="/upload" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col">
                    <label for="company">Empresa:</label>
                    <select class="form-control" id="company" name="company" required>
                        <option value="">Seleccionar Empresa</option>
                        <option value="enerwire">Enerwire</option>
                        <option value="onewire">Onewire</option>
                    </select>
                </div>
                <div class="col">
                    <label for="type">Tipo de Documento:</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">Seleccionar Tipo de Documento</option>
                        <option value="01">FE - Factura Electrónica</option>
                        <option value="03">CCFE - Comprobante de Credito Fiscal Electrónico</option>
                        <option value="04">NRE - Nota de Remision Electrónica</option>
                        <option value="05">NCE - Nota de Credito Electrónica</option>
                        <option value="06">NDE - Nota de Debito Electrónica</option>
                        <option value="07">CRE - Comprobante de Retención Electrónico</option>
                        <option value="11">FEXE - Factura de Exportación Electrónica</option>
                        <option value="14">FSEE - Factura de Sujeto Excluido Electrónica</option>
                    </select>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div id="additionalFields" style="display: none;">
                <div class="row">
                    <div class="col">
                        <label for="recintoFiscal">Recinto Fiscal</label>
                        <select name="recintoFiscal" id="recintoFiscal" class="form-control">
                            <option value="">Selecionar Recinto Fiscal</option>
                            @foreach($recintos as $recinto)
                            <option value="{{ $recinto->id }}">{{ $recinto->id.' - '.$recinto->valor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label for="regimen">Regimen de Exportación</label>
                        <select name="regimen" id="regimen" class="form-control">
                            <option value="">Seleccionar Regimen de Exportacion</option>
                            @foreach($regimenes as $regimen)
                            <option value="{{ $regimen->id }}" @if($regimen->id == 'EX-1.1000.000') selected @endif >{{ $regimen->id.' - '.$regimen->valor }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="comments">Observaciones:</label>
                    <textarea name="comments" id="comments" cols="30" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="file" class="form-label">Archivo:</label>
                    <input class="form-control" type="file" name="file" accept=".csv,.xls,.xlsx" required>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col">
                    <button class="btn btn-primary btn-lg submit" type="submit">
                        <span class="btn-txt">Cargar y Generar JSON</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true">
                            <i class="fas fa-spinner fa-pulse"></i>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" id="verPdfBtn">
                        Ver PDF
                    </button>
                </div>
            </div>
        </form>
    </div> 
</div>
@endsection

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $("#uploadform").submit(function() {
            $(".spinner-border").removeClass("d-none");
            $(".submit").attr("disabled", true);
            $(".btn-txt").text('Procesando...');
        });

        $("#sendBtn").click(function() {
            $(".spinner-border-send").removeClass("d-none");
            $(".submit-send").attr("disabled", true);
            $(".btn-txt-send").text('Procesando...');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Asociar una función al evento click del botón
        document.getElementById('verPdfBtn').addEventListener('click', function(){
            Swal.fire({
                title: 'Vista Preliminar',
                html: `<iframe src="{{ route('obtener.pdf') }}" style="width:100%;height:80vh;"></iframe>`,
                customClass: 'swal-wide',
                showConfirmButton: true,
                showCloseButton: true,
            });
        });
    });
</script>
<script>
    const typeSelect = document.getElementById('type');
    const additionalFieldsDiv = document.getElementById('additionalFields');

    typeSelect.addEventListener('change', function() {
        if(typeSelect.value === '11') {
            additionalFieldsDiv.style.display = 'block';
        } else {    
            additionalFieldsDiv.style.display = 'none';
        }
    });
</script>
@endsection
