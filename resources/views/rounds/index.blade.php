@extends('layouts.panel')

@section('styles')
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
<meta name="csrf_token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="mb-0">Detalle de Rondas</h1>
            </div>
            <div class="col text-right">
                <a href="{{ url('dashboard') }}" class="btn btn-sm btn-default">
                    Dashboard
                </a>
                <a href="{{ url('rounds/create') }}" class="btn btn-sm btn-success">
                    Registrar Ronda
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
    <!-- Tabla de Datos -->
    <div class="card-body">
        <table class="table table-striped table-bordered" name="roundsTable" id="roundsTable" style="width:100%">
            <thead>
            <tr>
                <th>Máquina</th>
                <th>Supervisor</th>
                <th>Turno</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Metros</th>
                <th>Velocidad</th>
                <th>Producto</th>
                <th>Eficiencia</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rounds as $round)
                <tr>
                    <td>{{ $round->machine->machine_name }}</td>
                    <td>{{ $round->user->username }}</td>
                    <td>{{ $round->shift }}</td>
                    <td>{{ $round->round_date }}</td>
                    <td>{{ $round->hour }}</td>
                    <td>{{ $round->produced_meters }}</td>
                    <td>{{ $round->production_speed }}</td>
                    <td>{{ optional($round->product)->product_name }}</td>
                    <td>{{ round($round->percentage_achievement, 2) }}%</td>
                    <td>
                        <a href="{{ route('rounds.show', ['id' => $round['id']]) }}" alt="Mostrar" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if ($user->role == 'admin' || $user->role == 'jeferondas')
                        <a href="{{ route('rounds.edit', ['id' => $round['id']]) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('rounds.destroy', ['id' => $round['id']]) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>`
    </div>
</div>
@endsection

@section('scripts')

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script>
$(document).ready( function () {
    $('#roundsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'csv',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'excel',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'pdf',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'print',
                className: 'btn btn-primary btn-sm'
            }
        ],
        "language": {
            "lengthMenu": "Mostrar " + 
                `<select class='custom-select custom-select-sm form-control form-control-sm'>
                    <option value='10'>10</option>
                    <option value='25'>25</option>
                    <option value='50'>50</option>
                    <option value='100'>100</option>
                    <option value='-1'>All</option>
                </select>` 
                + " items por página",
            "zeroRecords": "Nada encontrado - disculpa",
            "loadingRecords": "Cargando...",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "loading": "Cargando...",
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        }
    });
} );
</script>

@endsection