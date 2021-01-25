@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Nueva Maquina</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('machines') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('machines') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="machine_name">Nombre de la Maquina</label>
                <input type="text" name="machine_name" class="form-control" value="{{ old('machine_name') }}" required>
            </div>
            <div class="form-group">
                <label for="process">Proceso</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="process" class="custom-control-input" id="process1" type="radio"
                        @if(old('process', 'Trifilado') == 'Trifilado') checked @endif value="Trifilado">
                    <label for="process1" class="custom-control-label">Trifilado</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="process" class="custom-control-input" id="process2" type="radio"
                        @if(old('process', 'Cableado') == 'Cableado') checked @endif value="Cableado">
                    <label for="process2" class="custom-control-label">Cableado</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="process" class="custom-control-input" id="process3" type="radio"
                        @if(old('process', 'Fraccionado') == 'Fraccionado') checked @endif value="Fraccionado">
                    <label for="process3" class="custom-control-label">Fraccionado</label>
                </div>
            </div>
            <div class="form-group">
                <label for="warehouse">Nave</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="warehouse" class="custom-control-input" id="warehouse1" type="radio"
                        @if(old('warehouse', 'AL') == 'AL') checked @endif value="AL">
                    <label for="warehouse1" class="custom-control-label">Aluminio (AL)</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="warehouse" class="custom-control-input" id="warehouse2" type="radio"
                        @if(old('warehouse') == 'CU') checked @endif value="CU">
                    <label for="warehouse2" class="custom-control-label">Cobre (CU)</label>
                </div>
            </div>
            <div id="div_device" class="form-group">
                <label for="device_id">Dispositivo</label>
                <select name="device_id" id="device_id" class="form-control">
                    <option value="">Seleccionar Dispositivo</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" @if(old('device_id') == $device->id) selected @endif>{{ $device->device_name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection