
    <table>
        <thead>
            <tr>
                <th>Año</th>
                <th>Mes</th>
                <th>Semana</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Turno</th>
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
                <td>Año</td>
                <td>Mes</td>
                <td>Semana</td>
                <td>Fecha</td>
                <td>Día</td>
                <td>Turno</td>
                <td>{{ $stop->machine->name }}</td>
                <td>Nave</td>
                <td>{{ $stop->operator->name }}</td>
                <td>
                @if($stop->product)
                {{ $stop->product->name }}
                @endif
                </td>
                <td>
                @if($stop->color)
                {{ $stop->color->name }}
                @endif
                </td>
                <td>Inicio Paro</td>
                <td>Fin Paro</td>
                <td>Tiempo de Paro</td>
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