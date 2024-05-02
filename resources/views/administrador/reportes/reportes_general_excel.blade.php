<table>
    <tr>
        <td colspan="2" style="background-color: black;color:white;text-align:center;">Reporte de Ganacias</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;width:200px;" >Total de Ventas</td>
        <td style="text-align:center;background-color: #D1E7DD;width:150px;"> {{$monto_ventas}}</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;">Total de Costos</td>
        <td style="text-align:center;background-color: #F8D7DA;"> {{$monto_com_by_vent}}</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;">Ganacia Neta</td>
        <td style="text-align:center;"> {{$monto_ventas-$monto_com_by_vent}}</td>
    </tr>
</table>
<br>
<table>
    <tr>
        <td colspan="2" style="background-color: black;color:white;text-align:center;">Reporte General</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;width:200px;" >Ventas</td>
        <td style="text-align:center;background-color: #D1E7DD;width:150px;"> {{$monto_ventas}}</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;">Compras</td>
        <td style="text-align:center;background-color: #F8D7DA;"> {{$monto_compras}}</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;">Gastos</td>
        <td style="text-align:center;background-color: #F8D7DA;"> {{$monto_gastos}}</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;">Devoluciones</td>
        <td style="text-align:center;background-color: #F8D7DA;"> {{$monto_devoluciones}}</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;">Ganancias</td>
        <td style="text-align:center;"> {{$monto_ventas-$monto_compras-$monto_gastos-$monto_devoluciones}}</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;">Deuda por Cobrar</td>
        <td style="text-align:center;"> {{$monto_deuda}}</td>
    </tr>
    <tr>
        <td style="background-color: black;color:white;">Ganancias con Deuda</td>
        <td style="text-align:center;"> {{$monto_ventas-$monto_compras+$monto_deuda-$monto_gastos-$monto_devoluciones}}</td>
    </tr>
</table>
<br>
<table>
    <thead class="table-light">
        <tr class="text-center">
            <th colspan="13" style="background-color: black;color:white;width:150px;text-align:center;">Reporte de Ventas</th>
        </tr>
        <tr class="text-center">
            <th style="background-color: black;color:white;width:150px;text-align:center;">Fecha</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Almacen</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Cliente</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Impuesto Porcentaje</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Impuesto</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Descuento</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Descuento Items</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Envio</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Total a Pagar</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Cantidad Recibida</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Monto Pago</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Monto Pendiente</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Cambio</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lista_ventas as $pventa)
        <tr>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->created_at }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->almacen_name }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->cliente_name }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->impuesto_porcentaje }}%</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->impuesto_monto }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->descuento }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->descuento_items }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->envio }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->total_pagar }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->cantidad_recibida }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->monto_pago }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->monto_pendiente }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $pventa->cambio }}</td>
        </tr>
        @empty
        @endforelse
            <tr>
                <td colspan="4" style="background-color: black;color:white;text-align:center;">
                    Total
                </td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('impuesto_monto')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('descuento')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('descuento_items')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('envio')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('total_pagar')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('cantidad_recibida')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('monto_pago')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('monto_pendiente')}}</td>
                <td class="text-center table-success" style="text-align: center;">{{$lista_ventas->sum('cambio')}}</td>
            </tr>
    </tbody>
</table>
<br>
<table>
    <thead>
        <tr>
            <th colspan="9" style="background-color: black;color:white;width:150px;text-align:center;">Reporte de Compras</th>
        </tr>
        <tr>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Fecha</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Referencia</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Proveedor</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Almacen</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Estado</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Total</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Pagado</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Debido</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Estado de Pago</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lista_compras as $compra)
            <tr>
                <td style="border: solid 1px black;text-align: center;">{{ $compra->fecha }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ "COM_".$compra->id }}</td>
                <td style="border: solid 1px black;text-align: center;">
                    @if (isset($compra->proveedor))
                        {{ $compra->proveedor->name }}
                    @endif
                </td>
                <td style="border: solid 1px black;text-align: center;">
                    @if (isset($compra->almacen))
                        {{ $compra->almacen->nombre }}
                    @endif
                </td>
                <td style="border: solid 1px black;text-align: center;">
                    @if ($compra->estado == 1)
                    <span class="badge text-bg-success">Recibido</span>
                    @elseif ($compra->estado == 2)
                    <span class="badge text-bg-primary">Pendiente</span>
                    @elseif ($compra->estado == 3)
                    <span class="badge text-bg-warning">Ordenado</span>
                    @endif
                </td>
                <td style="border: solid 1px black;text-align: center;">{{ $compra->total }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ $compra->pagocompras->sum('monto_pago') }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ ($compra->total-$compra->pagocompras->sum('monto_pago')) }}</td>
                <td style="border: solid 1px black;text-align: center;">
                    @if ($compra->estado_pago == 1)
                        No Pagado
                    @elseif($compra->estado_pago == 2)
                    Pagado
                    @elseif($compra->estado_pago == 3)
                        Parcial
                    @endif
                </td>
            </tr>
        @empty
        @endforelse
        <tr>
            <td colspan="5" style="background-color: black;color:white;text-align:center;">
                Total
            </td>
            <td class="text-center table-success" style="text-align: center;">{{$lista_compras->sum('total')}}</td>
        </tr>
    </tbody>
</table>
<br>
<table>
    <thead>
        <tr>
            <th colspan="5" style="background-color: black;color:white;width:150px;text-align:center;">Reporte de Gastos</th>
        </tr>
        <tr>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Fecha</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Referencia</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Detalles</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Monto</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Almacen</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lista_gastos as $gasto)
            <tr>
                <td style="border: solid 1px black;text-align: center;">{{ $gasto->fecha }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ "EXP_".$gasto->id }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ $gasto->detalles }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ $gasto->monto }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ $gasto->almacen->nombre }}</td>
            </tr>
        @empty
        @endforelse
        <tr>
            <td colspan="3" style="background-color: black;color:white;text-align:center;">
                Total
            </td>
            <td class="text-center table-success" style="text-align: center;">{{$lista_gastos->sum('monto')}}</td>
        </tr>
    </tbody>
</table>
<br>
<table>
    <thead>
        <tr>
            <th colspan="6" style="background-color: black;color:white;width:150px;text-align:center;">Reporte de Devoluciones</th>
        </tr>
        <tr>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Fecha</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Recibo Dev</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Comprobante</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Almacen</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Nombre del Cliente</th>
            <th style="background-color: black;color:white;width:150px;text-align:center;">Monto Pago</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lista_devoluciones as $devolucion)
            <tr>
                <td style="border: solid 1px black;text-align: center;">{{ $devolucion->created_at }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ "DEV_".$devolucion->id }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ "COM_".$devolucion->posventa->id }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ $devolucion->almacen->nombre }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ $devolucion->cliente_name }}</td>
                <td style="border: solid 1px black;text-align: center;">{{ $devolucion->total_pagar }}</td>
            </tr>
        @empty
        @endforelse
        <tr>
            <td colspan="5" style="background-color: black;color:white;text-align:center;">
                Total
            </td>
            <td class="text-center table-success" style="text-align: center;">{{$lista_devoluciones->sum('total_pagar')}}</td>
        </tr>
    </tbody>
</table>




