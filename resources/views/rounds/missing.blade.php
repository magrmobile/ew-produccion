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
                <h2 class="mb-0">Rondas Faltantes</h2>
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
    <!-- Estadisticas de Eficiencia -->
    <div class="card-body">
        <table id="missingRoundsTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Maquina</th>
                    <th>Fecha Faltante</th>
                    <th>Hora Faltante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($missingRounds as $round)
                <tr>
                    <td>{{ $round['machine_name'] }}</td>
                    <td>{{ $round['round_date'] }}</td>
                    <td>{{ $round['hour'] }}</td>
                    <td>
                        <a href="{{ route('rounds.create', ['machine_id' => $round['machine_id'], 'hour' => $round['hour'], 'round_date' => $round['round_date']]) }}" class="btn btn-primary btn-sm">
                            Ingresar ronda
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
    $('#missingRoundsTable').DataTable({
        responsive: true,
        autoWidth: true,
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