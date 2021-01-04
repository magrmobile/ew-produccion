@extends('layouts.panel')

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
                <select name="code_id" id="code_id" class="form-control" required onchange="cargarFormulario(this)">
                    <option value="">Seleccionar Codigo de Paro</option>
                    @foreach($codes as $code)
                        <option value="{{ $code->id }}" @if(old('code',$stop->code_id) == $code->id) selected @endif>{{ $code->code." - ".$code->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="type_stop">Tipo de Paro</label>
                <input name="type_stop" id="type_stop" type="text" class="form-control" value="{{ old('type_stop',$stop->code->type) }}" readonly>
            </div>
            </div>
            <div id="div_machine" class="form-group">
                <label for="machine">Maquina</label>
                <select name="machine_id" id="machine_id" class="form-control" data-live-search="true">
                    <option value="">Seleccionar Maquina</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}" @if(old('machine_id',$stop->machine_id) == $machine->id) selected @endif>{{ $machine->machine_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <div id="div_product" class="form-group col-md-6" style="display:none;">
                    <label for="product">Producto</label>
                    <select name="product_id" id="product_id" class="form-control">
                        <option value="">Seleccionar Producto</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @if(old('product_id',$stop->product_id) == $product->id) selected @endif>{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="div_color" class="form-group col-md-6" style="display:none;">
                    <label for="color">Color</label>
                    <select name="color_id" id="color_id" class="form-control">
                        <option value="">Seleccionar Color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" @if(old('color_id',$stop->color_id) == $color->id) selected @endif style="background-color:{{ $color->hex_code }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                    <!--<input type="color" class="form-control" name="color" id="color">-->
                </div>
            </div>
            <div id="div_meters" class="form-group" style="display:none;">
                <label for="meters">Metros Producidos</label>
                <input name="meters" value="{{ old('meters', $stop->meters) }}" id="meters" type="text" class="form-control" placeholder="Ingrese la cantidad de metros producidos">
            </div>
            <div id="div_comment" class="form-group">
                <label for="comment">Observaciones</label>
                <input name="comment" value="{{ old('comment', $stop->comment) }}" id="comment" class="form-control" placeholder="Ingresa un comentario">
            </div>
            <button class="btn btn-primary" type="submit">
                Guardar
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!--<script src="{{ asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>-->
<script src="{{ asset('/js/stops/create.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
</html>
<script>
    $('.colorpicker').colorpicker();
</script>
@endsection