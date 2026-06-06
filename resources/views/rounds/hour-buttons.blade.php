<div class="form-group">
@forelse ($hours as $hour)
    <button type="button" class="btn btn-primary" onclick="submitForm('{{ $hour }}')">
        {{ $hour }}
    </button>
@empty
    <div class="alert alert-info" role="alert">
        No hay horas pendientes para esta maquina y fecha dentro de las ultimas 24 horas.
    </div>
@endforelse
</div>
