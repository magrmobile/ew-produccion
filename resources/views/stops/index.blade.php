@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Mis Paros</h3>
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
                <th scope="col">Fecha Inicio</th>
                @if($role == "supervisor" || $role == "admin")
                <th scope="col">Fin Paro</th>
                <th scope="col">Fecha Fin</th>
                <th scope="col">Operador</th>
                <th scope="col">Status</th>
                @endif
                <th scope="col">Maquina</th>
                <th scope="col">Motivo Paro</th>
                <th scope="col">Tipo Paro</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stops as $stop)
            <tr>
            <th scope="row">
                {{ $stop->stop_time_start }}
            </th>
            <td>
                {{ $stop->stop_date_start }}
            </td>
            @if($role == "supervisor" || $role == "admin")
                <td>{{ $stop->stop_time_end }}</td>
                <td>{{ $stop->stop_date_end }}</td>
                <td>{{ $stop->operator->name }}</td>
                <td>
                    @if($stop->stop_time_end != null) 
                    <span class="badge badge-pill badge-success">Finalizado</span> 
                    @else 
                    <span class="badge badge-pill badge-info">Activo</span> 
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
                {{ $stop->code->type }}
            </td>
            <td>
                @if($role == 'admin')
                <a class="btn btn-sm btn-primary" title="Ver Cita" href="{{ url('/stops/'.$stop->id) }}">
                    Ver
                </a>
                @endif
                <a class="btn btn-sm btn-danger" title="Cancelar Cita" href="{{ url('/stops/'.$stop->id.'/cancel') }}">
                    Cancelar
                </a>
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