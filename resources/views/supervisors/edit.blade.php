@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Editar Supervisor</h3>
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
        <form action="{{ url('supervisors/'.$supervisor->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nombre del Supervisor</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $supervisor->name) }}" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text" name="email" class="form-control" value="{{ old('email', $supervisor->email) }}">
            </div>
            <div class="form-group">
                <label for="dni">DNI</label>
                <input type="text" name="dni" class="form-control" value="{{ old('dni', $supervisor->dni) }}">
            </div>
            <div class="form-group">
                <label for="phone">Teléfono / Móvil</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $supervisor->phone) }}">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="text" name="password" class="form-control" value="">
                <p>Ingrese un valor solo si desea modificar la contraseña</p>
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection