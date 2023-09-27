<div class="form-group">
@php
    $time = date("H").":00";
@endphp
@foreach ($hours as $index => $hour)
    <button type="button" class="btn btn-primary" onclick="submitForm('{{ $hour }}')" {{ (isset($existingRounds[$hour]) || $hour > $time) ? 'disabled' : '' }}>
        {{ $hour }}
    </button>
@endforeach
</div>