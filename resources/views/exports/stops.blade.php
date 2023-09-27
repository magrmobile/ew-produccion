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
            <td>{{ $stop->year }}</td>
            <td>{{ $stop->month }}</td>
            <td>{{ $stop->week }}</td>
            <td>{{ $stop->date }}</td>
            <td>{{ $stop->dayname_end }}</td>
            <td>{{ $stop->schedule }}</td>
            <td>{{ $stop->process }}</td>
            <td>{{ $stop->machine_name }}</td>
            <td>{{ $stop->warehouse }}</td>
            <td>{{ $stop->operator_name }}</td>
            <td>{{ $stop->product_name }}</td>
            <td>{{ $stop->color_name }}</td>
            <td>{{ $stop->stop_start }}</td>
            <td>{{ $stop->stop_end }}</td>
            <td>{{ $stop->duration }}</td>
            <td>{{ $stop->stop_code }}</td>
            <td>{{ $stop->code_description }}</td>
            <td>{{ $stop->stop_type }}</td>
            <td>{{ $stop->conversion_description }}</td>
            <td>{{ $stop->quantity }}</td>
            <td>{{ $stop->meters }}</td>
            <td>{{ $stop->comment }}</td>
        </tr>
        @endforeach
    </tbody>
</table>