@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Editar Operador</h3>
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
        <form action="{{ url('operators/'.$operator->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Usuario</label>
                <input type="text" name="username" class="form-control" value="{{ old('username', $operator->username) }}" required>
            </div>
            <div class="form-group">
                <label for="name">Nombre del Operador</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $operator->name) }}" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="text" name="password" class="form-control" value="">
                <p>Ingrese un valor solo si desea modificar la contraseña</p>
            </div>
            <div id="div_process" class="form-group">
                <label for="process">Proceso</label>
                <select name="process_id" id="process_id" class="form-control form-control-sm" data-live-search="true">
                    <option value="">Seleccionar Proceso</option>
                    @foreach($processes as $process)
                        <option value="{{ $process->id }}" @if(old('process_id',$operator->process_id) == $process->id) selected @endif>{{ $process->description }}</option>
                    @endforeach
                </select>
            </div>
            <div id="div_supervisor" class="form-group">
                <label for="supervisor_id">Supervisor</label>
                <select name="supervisor_id" id="supervisor_id" class="form-control form-control-sm" data-live-search="true">
                    <option value="">Seleccionar Supervisor</option>
                    @foreach($supervisors as $supervisor)
                        <option value="{{ $supervisor->id }}" @if(old('supervisor_id',$operator->supervisor_id) == $supervisor->id) selected @endif>{{ $supervisor->name }}</option>
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