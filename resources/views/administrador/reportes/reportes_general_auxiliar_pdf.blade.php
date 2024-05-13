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
            <td>FECHA</td>
            <td>DESCRIPCIÓN</td>
            <td>PROVEEDOR/COMUNIDAD</td>
            <td>NOMBRE DE LA CUENTA</td>
            <td>Docto. No.</td>
            <td>INGRESOS</td>
            <td>EGRESOS</td>
            <td>SALDO</td>
            <td>ACCIÓN</td>
            <td>T. MOVIMIENTOS</td>
        </tr>
        @foreach ($movimientos as $mov)
        <tr>
            <td>{{ $mov->created_at }}</td>
            <td>
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
            <td>Varios</td>
            <td>
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
            <td>
                {{$mov->movimientoable_id}}
            </td>
            <td>
                @if ($mov->tipo_movimiento == '+')
                    {{$mov->monto}}
                @else
                0
                @endif
            </td>
            <td>
                @if ($mov->tipo_movimiento == '-')
                    {{$mov->monto}}
                @else
                0
                @endif
            </td>
            <td>{{ $mov->saldo }}</td>
            <td>{{ $mov->accion }}</td>
            <td>{{ $mov->tipo_movimiento }}</td>
        </tr>
        @endforeach
    </table>


</body>
</html>
