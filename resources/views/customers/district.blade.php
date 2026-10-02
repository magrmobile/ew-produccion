<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="distrito">Distrito</label>
            <select name="distrito" id="distrito" class="form-control">
                <option value="">Seleccionar Distrito</option>
                @foreach($distritos as $distrito)
                    <option value="{{ $distrito->id }}"
                        data-departamento="{{ $distrito->departamento }}"
                        data-municipio="{{ $distrito->municipio }}"
                        {{ (string) old('distrito', isset($customer) ? $customer->distrito : '') === (string) $distrito->id ? 'selected' : '' }}>
                        {{ $distrito->id.' - '.$distrito->valor }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
