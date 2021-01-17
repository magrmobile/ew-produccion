@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Nuevo Dispositivo</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('devices') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('devices') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="serial_number">Numero de Serie</label>
                <input type="text" id="serial_number" name="serial_number" class="form-control" value="{{ old('serial_number') }}" required>
            </div>
            <div class="form-group">
                <label for="mac_address">MAC Address</label>
                <input type="text" id="mac_address" name="mac_address" class="form-control" value="{{ old('mac_address') }}">
            </div>
            <div class="form-group">
                <label for="device_name">Nombre Dispositivo</label>
                <input type="text" name="device_name" class="form-control" value="{{ old('device_name') }}">
            </div>
            <div class="form-group">
                <label for="description">Descripción</label>
                <input type="text" name="description" class="form-control" value="{{ old('description') }}">
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById("mac_address").addEventListener('keyup', function() {
        this.value = (this.value.toUpperCase().replace(/[^\d|A-Z]/g, '').match(/.{1,2}/g) || []).join(":")
    });
</script>
@endsection