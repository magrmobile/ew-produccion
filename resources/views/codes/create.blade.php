@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Nuevo Motivo</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('codes') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('codes') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="description">Descripcion</label>
                <input type="text" name="description" class="form-control" value="{{ old('description') }}" required>
            </div>
            <div class="form-group">
                <label for="type">Tipo de Paro</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="type" class="custom-control-input" id="type1" type="radio"
                        @if(old('type', 'Programado') == 'Programado') checked @endif value="Programado">
                    <label for="type1" class="custom-control-label">Programado</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="type" class="custom-control-input" id="type2" type="radio"
                        @if(old('type') == 'No Programado') checked @endif value="No Programado">
                    <label for="type2" class="custom-control-label">No Programado</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection