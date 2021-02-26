@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Mis Paros</h3>
            </div>
            <div class="col text-right">
                <a href="{{ url('stops/create') }}" class="btn btn-sm btn-success">
                    Nuevo Paro
                </a>
                <a href="{{ route('stops.excel') }}" class="btn btn-sm btn-success">
                    Descargar Paros
                </a>
                <a href="{{ route('stops.excel') }}" class="btn btn-sm btn-success">
                    Descargar Paros
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
        <!-- Stops table -->
        <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">Inicio Paro</th>
                <th scope="col">Fin Paro</th>
                @if($role == "supervisor" || $role == "admin")
                <th scope="col">Operador</th>
                <th scope="col">Status</th>
                @endif
                <th scope="col">Maquina</th>
                <th scope="col">Motivo Paro</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stops as $stop)
            <tr>
            <td scope="row">
                {{ $stop->stop_datetime_start }}
            </td>
            <td scope="row">
                {{ $stop->stop_datetime_end }}
            </td>
            @if($role == "supervisor" || $role == "admin")
                <td>{{ $stop->operator->name }}</td>
                <td>
                    @if($stop->stop_datetime_end != null) 
                    <span class="badge badge-pill badge-info">Finalizado</span> 
                    @else 
                    <span class="badge badge-pill badge-success">Activo</span> 
                    @endif
                </td>
            @endif
            <td>
                {{ $stop->machine->machine_name }}
            </td>
            <td>
                {{ $stop->code->description }}
            </td>
            <td>
                <a class="btn btn-sm btn-primary" title="Ver Paro" href="{{ url('/stops/'.$stop->id) }}">
                    Ver
                </a>
                <!--<a class="btn btn-sm btn-primary" title="Ver Paro" href="{{ url('/stops/'.$stop->id.'/edit') }}">
                    Editar
                </a>-->
                <form action="{{ url('/stops/'.$stop->id) }}" method="POST" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/stops/'.$stop->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    <div class="card-body">
        {{ $stops->links() }}
    </div>
</div>
@endsection