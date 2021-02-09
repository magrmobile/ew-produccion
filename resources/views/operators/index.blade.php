@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Operadores</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('operators/create') }}" class="btn btn-sm btn-success">
                Nuevo Operador
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
            <th scope="col">Usuario</th>
            <th scope="col">Nombre</th>
            <th scope="col">Supervisor</th>
            <th scope="col">Maquina</th>
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($operators as $operator)
            <tr>
            <td scope="row">
                {{ $operator-> username }}
            </td>
            <td scope="row">
                {{ $operator-> name }}
            </td>
            <td>
                <!-- Supervisor -->
            </td>
            <td scope="row">
                {{ $operator->machine['machine_name'] }}
            </td>
            <td>
                <form action="{{ url('/operators/'.$operator->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/operators/'.$operator->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
                
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="card-body">
            {{ $operators->links() }}
        </div>
    </div>
    
</div>
@endsection