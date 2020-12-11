@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Colores</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('colors/create') }}" class="btn btn-sm btn-success">
                Nuevo Color
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
            <th scope="col">Color</th>
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($colors as $color)
            <tr>
            <th scope="row">
                {{ $color-> name }}
            </th>
            <td>
            <div class="card" style="background-color:{{ $color->hex_code }};width:3rem;">
                <div class="card-body text-center d-flex justify-content-center align-items-center">
                </div>
            </div>
            </td>
            <td>
                <form action="{{ url('/colors/'.$color->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ url('/colors/'.$color->id.'/edit') }}" class="btn btn-sm btn-primary">Editar</a>
                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="card-body">
            {{ $colors->links() }}
        </div>
    </div>
@endsection