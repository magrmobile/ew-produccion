@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Nuevo Supervisor</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('supervisors') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('supervisors') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            </div>
            <div class="form-group">
                <label for="name">Nombre del Supervisor</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text" name="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="text" name="password" class="form-control" value="{{ str_random(6) }}">
            </div>
            <div class="form-group">
                <label for="shift">Turno</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="shift" class="custom-control-input" id="shift1" type="radio"
                        @if(old('shift', 'D') == 'D') checked @endif value="D">
                    <label for="shift1" class="custom-control-label">Diurno</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="shift" class="custom-control-input" id="shift2" type="radio"
                        @if(old('shift') == 'N') checked @endif value="N">
                    <label for="shift2" class="custom-control-label">Nocturno</label>
                </div>
            </div>
            <div class="form-group">
                <label for="warehouse">Nave</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="warehouse" class="custom-control-input" id="warehouse1" type="radio"
                        @if(old('warehouse', 'AL') == 'AL') checked @endif value="AL">
                    <label for="warehouse1" class="custom-control-label">Aluminio</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="warehouse" class="custom-control-input" id="warehouse2" type="radio"
                        @if(old('warehouse') == 'CU') checked @endif value="CU">
                    <label for="warehouse2" class="custom-control-label">Cobre</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection