@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-1">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Velocidades por Maquina y Producto</h3>
            </div>
            <div class="col text-right">
                <a href="{{ route('machine-products.create') }}" class="btn btn-sm btn-success">
                    Nueva Velocidad
                </a>
            </div>
        </div>
    </div>

    @if(session('notification'))
    <div class="card-body">
        <div class="alert alert-success" role="alert">
            {{ session('notification') }}
        </div>
    </div>
    @endif

    <div class="card-body">
        <form method="GET" action="{{ route('machine-products.index') }}" class="mb-4">
            <div class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label for="process_id">Proceso</label>
                    <select name="process_id" id="process_id" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        @foreach($processes as $process)
                            <option value="{{ $process->id }}" @if(request('process_id') == $process->id) selected @endif>
                                {{ $process->description }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="machine_id">Maquina</label>
                    <select name="machine_id" id="machine_id" class="form-control form-control-sm">
                        <option value="">Todas</option>
                        @foreach($machines as $machine)
                            <option value="{{ $machine->id }}" @if(request('machine_id') == $machine->id) selected @endif>
                                {{ $machine->machine_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="product_id">Producto</label>
                    <select name="product_id" id="product_id" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @if(request('product_id') == $product->id) selected @endif>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                    <a href="{{ route('machine-products.index') }}" class="btn btn-sm btn-default">Limpiar</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Proceso</th>
                        <th>Maquina</th>
                        <th>Producto</th>
                        <th>Velocidad</th>
                        <th>Creado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($speeds as $speed)
                    <tr>
                        <td>{{ optional(optional($speed->machine)->process)->description }}</td>
                        <td>{{ optional($speed->machine)->machine_name }}</td>
                        <td>{{ optional($speed->product)->product_name }}</td>
                        <td>{{ $speed->speed }}</td>
                        <td>{{ optional($speed->user)->name }}</td>
                        <td>
                            <a href="{{ route('machine-products.edit', ['machine' => $speed->machine_id, 'product' => $speed->product_id]) }}" class="btn btn-sm btn-primary">
                                Editar
                            </a>
                            <form action="{{ route('machine-products.destroy', ['machine' => $speed->machine_id, 'product' => $speed->product_id]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de eliminar este registro?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay velocidades registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $speeds->links() }}
        </div>
    </div>
</div>
@endsection
