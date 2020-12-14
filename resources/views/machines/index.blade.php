@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Maquinas</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('machines/create') }}" class="btn btn-sm btn-success">
                Nueva Maquina
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
            <th scope="col">Codigo</th>
            <th scope="col">Nave</th>
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($machines as $machine)
            <tr>
            <th scope="row">
                {{ $machine-> machine_name }}
            </th>
            <td>
                {{ $machine-> machine_code }}
            </td>
            <td>
                @if($machine-> warehouse == "AL")
                    Aluminio (AL)
                @elseif($machine-> warehouse == "CU")
                    Cobre (CU)
                @endif
            </td>
            <td>
                <form action="{{ url('/machines/'.$machine->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/machines/'.$machine->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
                
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="card-body">
            {{ $machines->links() }}
        </div>
    </div>
    
</div>
@endsection