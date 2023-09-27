@extends('layouts.panel')

@section('styles')
<meta name="csrf_token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header border-1">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Maquinas</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('machines/create') }}" class="btn btn-sm btn-success">
                Nueva Maquina
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
            <table class="table table-striped dt-responsive nowrap" id="machines" style="width:100%">
                <thead>
                    <tr>
                    <th>Nombre</th>
                    <th>Proceso</th>
                    <th>Tablet</th>
                    <th>Nave</th>
                    <th>Locacion</th>
                    <th>Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
</div>
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

var table = $('#machines').DataTable({
    "ajax": "{{ route('datatable.machines') }}",
    "columns": [
        { data: "machine_name" },
        { data: "process_name" },
        { data: "device_name" },
        { data: "warehouse" },
        { data: "location" },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true,
    autoWidth: false,
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
                url: "{{ url('machines') }}" + '/' + id,
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