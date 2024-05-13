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
    <table>
        <tr>
            <td colspan="8" style="text-align:center;">{{$configuracion->name}}</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align:center;">{{$configuracion->descripcion}}</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align:center;">{{$configuracion->descripcion2}}</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align:center;">Libro Auxiliar de caje del  {{$finicio}} al {{$ffinal}}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">FECHA</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">DESCRIPCIÓN</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">PROVEEDOR/COMUNIDAD</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">NOMBRE DE LA CUENTA</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">Docto. No.</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">INGRESOS</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">EGRESOS</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">SALDO</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">ACCIÓN</td>
            <td style="text-align:center;border:solid 1px #0a0a0a;background-color: #9b9b9b;">T. MOVIMIENTOS</td>
        </tr>
        @foreach ($movimientos as $mov)
        <tr>
            <td style="border: solid 1px black;text-align: center;">{{ $mov->created_at }}</td>
            <td style="border: solid 1px black;text-align: center;">
                @if ($mov->movimientoable_type == 'App\Models\Posventa')
                    @foreach ($mov->movimientoable->posventadetalles as $tey => $item)
                        {{$item->producto_nombre}}
                        @if ($mov->movimientoable->posventadetalles->count()-1 <> $tey)
                        ,
                        @endif
                    @endforeach
                @elseif($mov->movimientoable_type == 'App\Models\PagoCompra')
                    {{'COM_'.$mov->movimientoable->compra->id}}
                @elseif($mov->movimientoable_type == 'App\Models\Devolucion')
                    {{$mov->movimientoable->cliente_name}}
                @elseif($mov->movimientoable_type == 'App\Models\Gasto')
                    {{$mov->movimientoable->detalles}}
                @endif
            </td>
            <td style="border: solid 1px black;text-align: center;">Varios</td>
            <td style="border: solid 1px black;text-align: center;">
                @if ($mov->movimientoable_type == 'App\Models\Posventa')
                    Venta
                @elseif($mov->movimientoable_type == 'App\Models\PagoCompra')
                    Compras
                @elseif($mov->movimientoable_type == 'App\Models\Devolucion')
                    Devoluciones
                @elseif($mov->movimientoable_type == 'App\Models\Gasto')
                    Gastos
                @endif
            </td>
            <td style="border: solid 1px black;text-align: center;">
                {{$mov->movimientoable_id}}
            </td>
            <td style="border: solid 1px black;text-align: center;">
                @if ($mov->tipo_movimiento == '+')
                    {{$mov->monto}}
                @else
                0
                @endif
            </td>
            <td style="border: solid 1px black;text-align: center;">
                @if ($mov->tipo_movimiento == '-')
                    {{$mov->monto}}
                @else
                0
                @endif
            </td>
            <td style="border: solid 1px black;text-align: center;">{{ $mov->saldo }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $mov->accion }}</td>
            <td style="border: solid 1px black;text-align: center;">{{ $mov->tipo_movimiento }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
