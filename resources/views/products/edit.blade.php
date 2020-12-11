@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Editar Producto</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('products') }}" class="btn btn-sm btn-default">
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
        <form action="{{ url('products/'.$product->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="product_name">Nombre</label>
                <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" required>
            </div>
            <div class="form-group">
                <label for="metal_type">Tipo de Metal</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="metal_type" class="custom-control-input" id="metal_type1" type="radio"
                        @if($product->metal_type == 'AL') checked @endif value="AL">
                    <label for="metal_type1" class="custom-control-label">AL</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="metal_type" class="custom-control-input" id="metal_type2" type="radio"
                        @if($product->metal_type == 'CU') checked @endif value="CU">
                    <label for="metal_type2" class="custom-control-label">CU</label>
                </div>
            </div>
            <div class="form-group">
                <label for="stock">PP o PT</label>
                <div class="custom-control custom-radio mb-3">
                    <input name="stock" class="custom-control-input" id="stock1" type="radio"
                        @if($product->stock == 'PP') checked @endif value="PP">
                    <label for="stock1" class="custom-control-label">PP</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="stock" class="custom-control-input" id="stock2" type="radio"
                        @if($product->stock == 'PT') checked @endif value="PT">
                    <label for="stock2" class="custom-control-label">PT</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection