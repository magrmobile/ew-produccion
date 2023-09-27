@extends('layouts.panel')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/select2-bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Ingreso de Datos de Produccion</h3>
            </div>
            <div class="col text-right">
                <a href="{{ url('dashboard') }}" class="btn btn-sm btn-default">
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
        <form id="round_form_create" action="{{ url('rounds') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="round_date">Fecha</label>
                <input type="date" class="form-control" id="round_date" name="round_date" value="{{ $round->round_date }}">
            </div>

            <div class="form-group">
                <label for="machine_id">Máquina</label>
                <select name="machine_id" id="machine_id" class="form-control">
                    <option value="">Seleccionar Maquina...</option>
                    @foreach ($machines as $machine)
                    <option value="{{ $machine->id }}" @if(old('machine_id',$round->machine_id) == $machine->id) selected @endif>{{ $machine->machine_name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="div_product" class="form-group">
                <label for="product_id">Producto Producido</label>
                <select name="product_id" id="product_id" class="form-control">
                    <option value=""></option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" @if(old('product_id') == $product->id) selected @endif>{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="production_speed">Velocidad de producción</label>
                <input type="number" class="form-control" id="production_speed" name="production_speed" value="{{ old('production_speed') }}">
            </div>

            <div class="form-group">
                <label for="produced_meters">Metros producidos</label>
                <input type="number" class="form-control" id="produced_meters" name="produced_meters" value="{{ old('produced_meters') }}">
            </div>

            <div class="form-group">
                <label for="code_id">Código</label>
                <select name="code_id" id="code_id" class="form-control">
                    <option value="">Seleccione un código</option>
                    @foreach($codes as $code)
                        <option value="{{ $code->id }}">{{ $code->code.' - '.$code->description }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group" id="no_production_reason" style="display:none;">
                <label for="no_production_reason">Motivo no produccion</label>
                <input type="text" name="no_production_reason" class="form-control" value="{{ old('no_production_reason') }}">
            </div>
            
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@endsection