<table class="table table-hover">
    <thead class="table-light">
        <tr class="text-center">
            <th style="background-color: black;color:white;width:150px;text-align:center;">Fecha</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Almacen</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Cliente</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Impuesto Porcentaje</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Impuesto</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Descuento</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Envio</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Total a Pagar</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lista_ventas as $pventa)
        <tr>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->created_at }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->almacen_name }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->cliente_name }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->impuesto_porcentaje }}%</td>
            <td style="border: solid 1px black;text-align: center;">{{ $configuracion->moneda->simbolo.$pventa->impuesto_monto }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $configuracion->moneda->simbolo.$pventa->descuento }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $configuracion->moneda->simbolo.$pventa->envio }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $configuracion->moneda->simbolo.$pventa->total_pagar }}</td>
        </tr>
        @empty
        @endforelse
            <tr>
                <td colspan="4" style="background-color: black;color:white;text-align:center;">
                    Total
                </td>
                <td class="text-center table-success" style="text-align: center;">{{$configuracion->moneda->simbolo.$lista_ventas->sum('impuesto_monto')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$configuracion->moneda->simbolo.$lista_ventas->sum('descuento')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$configuracion->moneda->simbolo.$lista_ventas->sum('envio')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$configuracion->moneda->simbolo.$lista_ventas->sum('total_pagar')}}</td>
            </tr>
    </tbody>
</table>
