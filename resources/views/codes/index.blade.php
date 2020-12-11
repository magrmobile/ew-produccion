@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Motivos de Paro</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('codes/create') }}" class="btn btn-sm btn-success">
                Nuevo Motivo
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
            <th scope="col">Codigo</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Tipo</th>
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($codes as $code)
            <tr>
            <th scope="row">
                {{ $code-> code }}
            </th>
            <td>
                {{ $code-> description }}
            </td>
            <td>
                {{ $code-> type }}
            </td>
            <td>
                <form action="{{ url('/codes/'.$code->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/codes/'.$code->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="card-body">
            {{ $codes->links() }}
        </div>
    </div>
    
</div>
@endsection