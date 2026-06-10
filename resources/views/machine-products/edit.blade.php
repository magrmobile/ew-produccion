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
                <h3 class="mb-0">Editar Velocidad</h3>
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
        <form action="{{ route('machine-products.update', ['machine' => $speed->machine_id, 'product' => $speed->product_id]) }}" method="post">
            @csrf
            @method('PUT')
            @include('machine-products.form', ['speed' => $speed])
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
</script>
@endsection
