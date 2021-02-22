@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Productos</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('products/create') }}" class="btn btn-sm btn-success">
                Nuevo Producto
            </a>
        </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('notification'))
        <div class="alert alert-success" role="alert">
            {{ session('notification') }}
        </div>
        @endif
    </div>
    <div class="table-responsive">
        <!-- Projects table -->
        <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Metal</th>
            <th scope="col">Familia</th>
            <th scope="col">Proceso</th>
            <th scope="col">Stock</th>
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
            <th scope="row">
                {{ $product-> product_name }}
            </th>
            <td>
                {{ $product-> metal_type }}
            </td>
            <td>
                @if($product->family)
                    {{ $product->family->family_name }}
                @endif
            </td>
            <td>
                @if($product->process)
                {{ $product->process->description }}
                @endif
            </td>
            <td>
                {{ $product->stock }}
            </td>
            <td>
                <form action="{{ url('/products/'.$product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/products/'.$product->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
                
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="card-body">
            {{ $products->links() }}
        </div>
    </div>
    
</div>
@endsection