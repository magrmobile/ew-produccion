@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Editar Color</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('colors') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('colors/'.$color->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $color->name) }}" required>
            </div>
            <div class="form-group">
                <label for="hex_code">Selecciona el Color</label>
                <input type="color" name="hex_code" class="form-control" style="padding:0;width:3rem;" value="{{ old('hex_code', $color->hex_code) }}">
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection