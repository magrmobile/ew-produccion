@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Dispositivos</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('devices/create') }}" class="btn btn-sm btn-success">
                Nueva Dispositivo
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
            <th scope="col">Numero de Serie</th>
            <th scope="col">Nombre</th>
            <th scope="col">Maquinas</th>
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devices as $device)
            <tr>
            <th scope="row">
                {{ $device-> serial_number }}
            </th>
            <td>
                {{ $device-> device_name }}
            </td>
            <td>
                <ul>
                @foreach($device->machines as $machine)
                    <li>{{ $machine->machine_name }}</li>
                @endforeach
                </ul>
            </td>
            <td>
                <form action="{{ url('/devices/'.$device->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/devices/'.$device->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
                
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="card-body">
            {{ $devices->links() }}
        </div>
    </div>
    
</div>
@endsection