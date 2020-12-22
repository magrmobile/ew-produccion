@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Paro #{{ $stop -> id }}</h3>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul>
            <li>
                <strong>Fecha Inicio Paro:</strong> {{ $stop->stop_date_start }}
            </li>
            <li>
                <strong>Hora Inicio Paro:</strong> {{ $stop->stop_time_start  }}
            </li>
            <li>
                <strong>Fecha Fin Paro:</strong> {{ $stop->stop_date_end }}
            </li>
            <li>
                <strong>Hora Fin Paro:</strong> {{ $stop->stop_time_end  }}
            </li>
            <li>
                <strong>Duracion de Paro:</strong> {{ $duration  }}
            </li>
            <li>
                <strong>Operador:</strong> {{ $stop->operator->name  }}
            </li>
            <li>
                <strong>Estado:</strong> 
                @if ($stop->stop_date_end != null)
                    <span class="badge badge-success">Finalizada</span>
                @else
                    <span class="badge badge-info">Activa</span>
                @endif
            </li>
            <li>
                <strong>Maquina:</strong> {{ $stop->machine->machine_name  }}
            </li>
            <li>
                <strong>Producto:</strong> {{ isset($stop->product->product_name) ? $stop->product->product_name : ''  }}
            </li>
            <li>
                <strong>Motivo de Paro:</strong> {{ $stop->code->description  }}
            </li>
            <li>
                <strong>Tipo de Paro:</strong> {{ $stop->code->type  }}
            </li>
            <li>
                <strong>Metros Producidos:</strong> {{ $stop->meters  }} Mts.
            </li>
            <li>
                <strong>Observaciones:</strong> {{ $stop->comment  }}
            </li>
        </ul>

        <a href="{{ url('/stops') }}" class="btn btn-default">
            Volver
        </a>
    </div>
</div>
@endsection