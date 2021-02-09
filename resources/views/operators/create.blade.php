@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Nuevo Operador</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('operators') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('operators') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            </div>
            <div class="form-group">
                <label for="name">Nombre del Operador</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="text" name="password" class="form-control" value="{{ str_random(6) }}">
            </div>
            <div id="div_machine" class="form-group">
                <label for="machine_id">Maquina</label>
                <select name="machine_id" id="machine_id" class="form-control form-control-sm" data-live-search="true">
                    <option value="">Seleccionar Maquina</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}" @if(old('machine_id') == $machine->id) selected @endif>{{ $machine->machine_name }}</option>
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