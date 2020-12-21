@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Supervisores</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('supervisors/create') }}" class="btn btn-sm btn-success">
                Nuevo Supervisor
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
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supervisors as $supervisor)
            <tr>
            <th scope="row">
                {{ $supervisor-> username }}
            </th>
            <th scope="row">
                {{ $supervisor-> name }}
            </th>
            <td>
                <form action="{{ url('/supervisors/'.$supervisor->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/supervisors/'.$supervisor->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
                
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="card-body">
            {{ $supervisors->links() }}
        </div>
    </div>
    
</div>
@endsection