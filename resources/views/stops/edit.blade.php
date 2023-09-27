@extends('layouts.panel')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/select2-bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">


@endsection

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Registrar Paro</h3>
        </div>
        <div class="col text-right">
            <a href="{{ url('stops') }}" class="btn btn-sm btn-default">
                Cancelar y volver
            </a>
        </div>
        </div>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ url('stops/'.$stop->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-row">
            <div class="form-group col-md-6">
                <label for="code">Codigo de Paro</label>
                <select name="code_id" id="code_id" class="form-control form-control-sm" required onchange="cargarFormulario(this)">
                    <option value="">Seleccionar Codigo de Paro</option>
                    @foreach($codes as $code)
                        <option value="{{ $code->id }}" @if(old('code',$stop->code_id) == $code->id) selected @endif>{{ $code->code." - ".$code->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="type_stop">Tipo de Paro</label>
                <input name="type_stop" id="type_stop" type="text" class="form-control form-control-sm" value="{{ old('type_stop',$stop->code->type) }}" readonly>
            </div>
            </div>
            <div id="div_machine" class="form-group">
                <label for="machine">Maquina</label>
                <select name="machine_id" id="machine_id" class="form-control form-control-sm" data-live-search="true">
                    <option value="">Seleccionar Maquina</option>
                    @foreach($machines as $machine)
                    <option value="{{ $machine->id }}" @if(old('machine_id',$stop->machine_id) == $machine->id) selected @endif>{{ $machine->machine_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <div id="div_product" class="form-group col-md-6" style="display:none;">
                    <label for="product">Producto</label>
                    <select name="product_id" id="product_id" class="form-control" style="height:100%">
                        <option value="">Seleccionar Producto</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @if(old('product_id',$stop->product_id) == $product->id) selected @endif>{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="div_color" class="form-group col-md-6" style="display:none;">
                    <label for="color">Color</label>
                    <select name="color_id" id="color_id" class="form-control form-control-sm">
                        <option value="">Seleccionar Color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" @if(old('color_id',$stop->color_id) == $color->id) selected @endif style="background-color:{{ $color->hex_code }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                    <!--<input type="color" class="form-control" name="color" id="color">-->
                </div>
            </div>
            <div class="form-row">
                <div id="div_conversion" class="form-group col-md-4" style="display:none;">
                    <label for="conversion_id">Conversión</label>
                    <select name="conversion_id" id="conversion_id" class="form-control form-control-sm" onchange="calcularConversion()">
                        <option value="">Seleccionar Conversión</option>
                        @foreach($conversions as $conversion)
                            <option value="{{ $conversion->id }}" @if(old('conversion_id',$stop->conversion_id) == $conversion->id) selected @endif>{{ $conversion->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="div_quantity" class="form-group col-md-4" style="display:none;">
                    <label for="quantity">Cantidad</label>
                    <input name="quantity" value="{{ old('quantity',$stop->quantity) }}" id="quantity" type="text" class="form-control form-control-sm" placeholder="Ingrese la Cantidad">
                </div>
                <div id="div_meters" class="form-group col-md-4" style="display:inline;">
                    <label for="meters">Metros Producidos</label>
                    <input name="meters" value="{{ old('meters',$stop->meters) }}" id="meters" type="text" class="form-control form-control-sm" placeholder="Ingrese la cantidad de metros producidos">
                </div>
            </div>
            <div id="div_comment" class="form-group">
                <label for="comment">Observaciones</label>
                <input name="comment" value="{{ old('comment', $stop->comment) }}" id="comment" class="form-control form-control-sm" placeholder="Ingresa un comentario">
            </div>
            @if($role == "supervisor" || $role == "admin")
            <div id="div_stop_start" class="form-group">
                <label for="stop_datetime_start">Inicio Paro</label>
                <input type="text" name="stop_datetime_start" id="stop_datetime_start" class="form-control form-control-sm">
            </div>
            <div id="div_stop_end" class="form-group">
                <label for="stop_datetime_end">Fin Paro</label>
                <input type="text" name="stop_datetime_end" id="stop_datetime_end" class="form-control form-control-sm">
            </div>
            @endif
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/stops/create.js') }}"></script>
<script src="{{ asset('/js/moment.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $('.colorpicker').colorpicker();
    $('#stop_datetime_start').datetimepicker({
        defaultDate: "{{ $stop->stop_datetime_start->format('Y-m-d H:i:s') }}",
        locale: 'es',
        icons: {
            time: 'far fa-clock',
            date: 'fas fa-calendar',
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-crosshairs',
            clear: 'fas fa-trash',
            close: 'fas fa-times'
        }
    });
    $('#stop_datetime_end').datetimepicker({
        defaultDate: "{{ $stop->stop_datetime_end->format('Y-m-d H:i:s') }}",
        locale: 'es',
        icons: {
            time: 'far fa-clock',
            date: 'fas fa-calendar',
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-crosshairs',
            clear: 'fas fa-trash',
            close: 'fas fa-times'
        },
    });
    $('#product_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccionar Producto',
        allowClear: true,
        ajax: {
            url: '/ajax-autocomplete-search',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item){
                        return {
                            text: item.product_name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
@endsection