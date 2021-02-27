<table>
    <thead>
        <tr>
            <th>Año</th>
            <th>Mes</th>
            <th>Semana</th>
            <th>Fecha</th>
            <th>Día</th>
            <th>Turno</th>
            <th>Proceso</th>
            <th>Maquina</th>
            <th>Nave</th>
            <th>Operadores</th>
            <th>Producto</th>
            <th>Color</th>
            <th>Inicio Paro</th>
            <th>Fin Paro</th>
            <th>Tiempo de Paro</th>
            <th>Código de Paro</th>
            <th>Causa de Paro</th>
            <th>Tipo de Paro</th>
            <th>Empaque</th>
            <th>Cantidad</th>
            <th>Prod. Mts.</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stops as $stop)
        <tr>
            <td>{{ $stop->stop_datetime_end->format('Y') }}</td>
            <td>{{ $stop->stop_datetime_end->format('m') }}</td>
            <td>{{ $stop->stop_datetime_end->format('w') }}</td>
            <td>{{ $stop->stop_datetime_end->format('j-m-Y') }}</td>
            <td>{{ $stop->dayname_end }}</td>
            <td>{{ $stop->schedule }}</td>
            <td>{{ $stop->machine->process->description }}</td>
            <td>{{ $stop->machine->machine_name }}</td>
            <td>{{ $stop->machine->warehouse }}</td>
            <td>{{ $stop->operator->name }}</td>
            <td>
            @if($stop->product)
            {{ $stop->product->product_name }}
            @endif
            </td>
            <td>
            @if($stop->color)
            {{ $stop->color->name }}
            @endif
            </td>
            <td>{{ $stop->stop_datetime_start->format('H:i:s') }}</td>
            <td>{{ $stop->stop_datetime_end->format('H:i:s') }}</td>
            <td>{{ $stop->duration }}</td>
            <td>{{ $stop->code->code }}</td>
            <td>{{ $stop->code->description }}</td>
            <td>{{ $stop->code->type }}</td>
            <td>
            @if($stop->conversion)
            {{ $stop->conversion->description }}
            @endif
            </td>
            <td>{{ $stop->quantity }}</td>
            <td>{{ $stop->meters }}</td>
            <td>{{ $stop->comment }}</td>
        </tr>
        @endforeach
    </tbody>
</table>