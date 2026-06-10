<div class="form-group">
    <label for="machine_id">Maquina</label>
    <select name="machine_id" id="machine_id" class="form-control" required>
        <option value="">Seleccionar Maquina</option>
        @foreach($machines as $machine)
            <option value="{{ $machine->id }}" @if(old('machine_id', isset($speed) ? $speed->machine_id : null) == $machine->id) selected @endif>
                {{ $machine->machine_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="product_id">Producto</label>
    <select name="product_id" id="product_id" class="form-control" required>
        <option value="">Seleccionar Producto</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}" @if(old('product_id', isset($speed) ? $speed->product_id : null) == $product->id) selected @endif>
                {{ $product->product_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="speed">Velocidad</label>
    <input type="number" step="0.01" min="0" name="speed" id="speed" class="form-control" value="{{ old('speed', isset($speed) ? $speed->speed : null) }}" required>
</div>

@if(!isset($speed))
<div id="existing-speed-message" class="alert alert-warning d-none" role="alert"></div>
@endif
