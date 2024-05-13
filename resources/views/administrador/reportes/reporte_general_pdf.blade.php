<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte General</title>
    <link href="{{asset('css/estilo_reporte.css')}}" rel="stylesheet" >
</head>
<body class="m-1 fs-12">
    <div>
        <div class="col-12">
            <table>
                <tr>
                    <td>
                        <h1 class="modal-title fs-5" id="modalReporteCajaLabel">Reporte de General @if ($nombre_titulo) - {{$nombre_titulo}} @endif</h1><br>
                    </td>
                    <td><img src="{{asset($configuracion->logo)}}" alt="" width="128px"></td>
                </tr>
            </table>
        </div>
    </div>
    <div>
        <table class="table table-hover">
            <tr>
                <td colspan="2" style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Reporte de Ganacias</td>
            </tr>
            <tr>
                <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;" >Total de Ventas</td>
                <td style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_ventas}}</td>
            </tr>
            <tr>
                <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Total de Costos</td>
                <td style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_com_by_vent}}</td>
            </tr>
            <tr>
                <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Ganacia Neta</td>
                <td style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_ventas-$monto_com_by_vent}}</td>
            </tr>
        </table>
    </div>
    <br>
    <div>
        <table class="table table-hover">
            <tr>
                <td colspan="2" style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Reporte General</td>
            </tr>
            <tr>
                <td  style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;" >Ventas</td>
                <td  style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_ventas}}</td>
            </tr>
            <tr>
                <td  style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Compras</td>
                <td  style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_compras}}</td>
            </tr>
            <tr>
                <td  style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Gastos</td>
                <td  style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_gastos}}</td>
            </tr>
            <tr>
                <td  style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Devoluciones</td>
                <td  style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_devoluciones}}</td>
            </tr>
            <tr>
                <td  style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Ganancias</td>
                <td  style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_ventas-$monto_compras-$monto_gastos-$monto_devoluciones}}</td>
            </tr>
            <tr>
                <td  style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Deuda por Cobrar</td>
                <td  style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_deuda}}</td>
            </tr>
            <tr>
                <td  style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Ganancias con Deuda</td>
                <td  style="text-align:center;border:solid 1px #0a0a0a"> {{$monto_ventas-$monto_compras+$monto_deuda-$monto_gastos-$monto_devoluciones}}</td>
            </tr>
        </table>
    </div>
    <br>
    <div>
        <table class="table table-hover">
            <thead class="table-light">
                <tr class="text-center">
                    <th colspan="13" style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Reporte de Ventas</th>
                </tr>
                <tr class="text-center">
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Fecha</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Almacen</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Cliente</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Impuesto Porcentaje</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Impuesto</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Descuento</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Descuento Items</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Envio</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Total a Pagar</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Cantidad Recibida</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Monto Pago</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Monto Pendiente</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Cambio</th>
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
    </div>
    <br>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th colspan="9" style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Reporte de Compras</th>
                </tr>
                <tr>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Fecha</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Referencia</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Proveedor</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Almacen</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Estado</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Total</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Pagado</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Debido</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Estado de Pago</th>
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
    </div>
    <br>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th colspan="5" style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Reporte de Gastos</th>
                </tr>
                <tr>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Fecha</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Referencia</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Detalles</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Monto</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Almacen</th>
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
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th colspan="6" style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Reporte de Devoluciones</th>
                </tr>
                <tr>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Fecha</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Recibo Dev</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Comprobante</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Almacen</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Nombre del Cliente</th>
                    <th style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Monto Pago</th>
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
    </div>
</body>
</html>
