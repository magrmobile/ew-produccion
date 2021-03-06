@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Editar Maquina</h3>
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
        <form action="{{ url('machines/'.$machine->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="machine_name">Nombre de la Maquina</label>
                <input type="text" name="machine_name" class="form-control" value="{{ old('machine_name', $machine->machine_name) }}" required>
            </div>
            <div class="form-group">
                <label for="machine_code">Codigo de la Maquina</label>
                <input type="text" name="machine_code" class="form-control" value="{{ old('machine_code', $machine->machine_code) }}">
            </div>
            <div id="div_process" class="form-group">
                <label for="process_id">Proceso</label>
                <select name="process_id" id="process_id" class="form-control">
                    <option value="">Seleccionar Proceso</option>
                    @foreach($processes as $process)
                        <option value="{{ $process->id }}" @if(old('process_id',$machine->process_id) == $process->id) selected @endif>{{ $process->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="warehouse">Nave</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="warehouse" class="custom-control-input" id="warehouse1" type="radio"
                        @if($machine->warehouse == 'AL') checked @endif value="AL">
                    <label for="warehouse1" class="custom-control-label">Aluminio (AL)</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="warehouse" class="custom-control-input" id="warehouse2" type="radio"
                        @if($machine->warehouse == 'CU') checked @endif value="CU">
                    <label for="warehouse2" class="custom-control-label">Cobre (CU)</label>
                </div>
            </div>
            <div id="div_device" class="form-group">
                <label for="device_id">Dispositivo</label>
                <select name="device_id" id="device_id" class="form-control">
                    <option value="">Seleccionar Dispositivo</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" @if(old('device_id',$machine->device_id) == $device->id) selected @endif>{{ $device->device_name }}</option>
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