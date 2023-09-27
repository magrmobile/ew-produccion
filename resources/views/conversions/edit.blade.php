@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Editar Conversion</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('conversions') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('conversions/'.$conversion->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="description">Descripcion</label>
                <input type="text" name="description" class="form-control" value="{{ old('description', $conversion->description) }}" required>
            </div>
            <div class="form-group">
                <label for="factor">Factor</label>
                <input type="text" name="factor" class="form-control" value="{{ old('factor', $conversion->factor) }}" required>
            </div>
            <div class="form-group">
                <label for="metal_type">Tipo</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="type" class="custom-control-input" id="type1" type="radio"
                        @if($conversion->type == 'R') checked @endif value="R">
                    <label for="type1" class="custom-control-label">R</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="type" class="custom-control-input" id="type2" type="radio"
                        @if($conversion->type == 'C') checked @endif value="C">
                    <label for="type2" class="custom-control-label">C</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection