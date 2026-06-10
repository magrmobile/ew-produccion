@extends('layouts.panel')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/select2-bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Nueva Velocidad</h3>
            </div>
            <div class="col text-right">
                <a href="{{ route('machine-products.index') }}" class="btn btn-sm btn-default">
                    Cancelar y volver
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('machine-products.store') }}" method="post">
            @csrf
            @include('machine-products.form')
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $('#machine_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccionar Maquina',
        allowClear: true
    });

    $('#product_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccionar Producto',
        allowClear: true
    });

    function checkExistingSpeed() {
        var machineId = $('#machine_id').val();
        var productId = $('#product_id').val();
        var message = $('#existing-speed-message');

        message.addClass('d-none').html('');

        if (!machineId || !productId) {
            return;
        }

        $.get("{{ route('machine-products.check') }}", {
            machine_id: machineId,
            product_id: productId
        }, function(response) {
            if (!response.exists) {
                return;
            }

            message
                .removeClass('d-none')
                .html(
                    'Esta combinacion ya existe con velocidad configurada de <strong>' + response.speed + '</strong>. ' +
                    '<a href="' + response.edit_url + '" class="alert-link">Desea editar esta combinacion?</a>'
                );
        });
    }

    $('#machine_id, #product_id').on('change', checkExistingSpeed);
    checkExistingSpeed();
</script>
@endsection
