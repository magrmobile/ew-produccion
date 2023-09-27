@extends('layouts.panel')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header border-1">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Documentos Tributarios Electronicos - Cliente: {{ $customer->nombre }}</h3>
            </div>
            <div class="col text-right">
                <a href="{{ url('customers/') }}" class="btn btn-sm btn-success">
                    Regresar
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
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped dt-responsive nowrap" id="dtes-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Codigo Generacion</th>
                        <th>Numero de Control</th>
                        <th>Fecha Creacion</th>
                        <th>Firmado</th>
                        <th>Transmitido</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@foreach($dtes as $dte)
    <div class="modal fade" id="viewModal{{ $dte->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $dte->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg"  role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel{{ $dte->id }}">Ver DTE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">Codigo Generacion:</div>
                            <div class="col-md-6">{{ $dte->codigoGeneracion }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Numero de Control:</div>
                            <div class="col-md-6">{{ $dte->numeroControl }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Creado Por:</div>
                            <div class="col-md-6">{{ $dte->user->username }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Fecha de Creacion:</div>
                            <div class="col-md-6">{{ $dte->created_at }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Contenido JSON:</div>
                            <div class="col-md-6">{{ $dte->json_dte }}</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#dtes-table').DataTable({
        "ajax": "{{ route('datatable.dtes',['customer_id' => $customer->id]) }}",
        "columns": [
            { data: "codigoGeneracion" },
            { data: "numeroControl" },
            { data: "created_at" },
            { data: "signed" },
            { data: "received" },
            { data: 'action', name: 'action', orderable: false, searchable: false }  
        ],
        responsive: true,
        autoWidth: false,
        columnDefs: [
            { targets: '_all', className: 'text-center' }, // Centra todas las celdas en todas las columnas
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

    function deleteData(id) {
        var csrf_token = $('meta[name="csrf_token"]').attr('content');
        Swal.fire({
            title: 'Esta Seguro?',
            text: "No se podra revertir!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('dtes') }}" + '/' + id,
                    type: "POST",
                    data: { '_method': 'DELETE', '_token': csrf_token },
                    success: function(data) {
                        console.log(csrf_token);
                        Swal.fire(
                            'Eliminado!',
                            'El registro ha sido eliminado.',
                            'success'
                        )
                        table.ajax.reload();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo Salio Mal!'
                        })
                    }
                });
            }
        });
    }
</script>
@endsection

