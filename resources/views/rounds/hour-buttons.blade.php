<div class="form-group">
@forelse ($hours as $hour)
    @php
        $exists = isset($existingRounds[$hour]);
    @endphp
    <button type="button" class="btn {{ $exists ? 'btn-info' : 'btn-primary' }}" onclick="submitForm('{{ $hour }}')" {{ $exists ? 'disabled' : '' }}>
        {{ $hour }}
    </button>
@empty
    <div class="alert alert-info" role="alert">
        Debes seleccionar la fecha y la maquina.
    </div>
@endforelse
</div>
