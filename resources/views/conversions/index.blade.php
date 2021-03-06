@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Conversiones</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('conversions/create') }}" class="btn btn-sm btn-success">
                Nueva Conversion
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
            <th scope="col">Descripcion</th>
            <th scope="col">Factor</th>
            <th scope="col">Tipo</th>
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($conversions as $conversion)
            <tr>
            <th scope="row">
                {{ $conversion-> description }}
            </th>
            <td>
                {{ $conversion-> factor }}
            </td>
            <td>
                {{ $conversion-> type }}
            </td>
            <td>
                <form action="{{ url('/conversions/'.$conversion->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/conversions/'.$conversion->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
                
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="card-body">
            {{ $conversions->links() }}
        </div>
    </div>
    
</div>
@endsection