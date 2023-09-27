@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Editar Proceso</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('processes') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('processes/'.$process->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="description">Descripcion</label>
                <input type="text" name="description" class="form-control" value="{{ old('description', $process->description) }}" required>
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection