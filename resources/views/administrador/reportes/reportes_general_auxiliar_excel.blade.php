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
        <td style="border:1px solid #000000;width:150px;text-align: center;">FECHA</td>
        <td style="border:1px solid #000000;width:240px;text-align: center;">DESCRIPCIÓN</td>
        <td style="border:1px solid #000000;width:180px;text-align: center;">PROVEEDOR/COMUNIDAD</td>
        <td style="border:1px solid #000000;width:180px;text-align: center;">NOMBRE DE LA CUENTA</td>
        <td style="border:1px solid #000000;width:120px;text-align: center;">Docto. No.</td>
        <td style="border:1px solid #000000;width:120px;text-align: center;">INGRESOS</td>
        <td style="border:1px solid #000000;width:120px;text-align: center;">EGRESOS</td>
        <td style="border:1px solid #000000;width:120px;text-align: center;">SALDO</td>
        <td style="border:1px solid #000000;width:120px;text-align: center;">ACCIÓN</td>
        <td style="border:1px solid #000000;width:120px;text-align: center;">T. MOVIMIENTOS</td>
    </tr>
    @foreach ($movimientos as $mov)
    <tr>
        <td style="border:1px solid #000000;text-align: center;">{{ $mov->created_at }}</td>
        <td style="border:1px solid #000000;text-align: center;">
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
        <td style="border:1px solid #000000;text-align: center;">Varios</td>
        <td style="border:1px solid #000000;text-align: center;">
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
        <td style="border:1px solid #000000;text-align: center;">
            {{$mov->movimientoable_id}}
        </td>
        <td style="border:1px solid #000000;text-align: center;">
            @if ($mov->tipo_movimiento == '+')
                {{$mov->monto}}
            @else
            0
            @endif
        </td>
        <td style="border:1px solid #000000;text-align: center;">
            @if ($mov->tipo_movimiento == '-')
                {{$mov->monto}}
            @else
            0
            @endif
        </td>
        <td style="border:1px solid #000000;text-align: center;">{{ $mov->saldo }}</td>
        <td style="border:1px solid #000000;text-align: center;">{{ $mov->accion }}</td>
        <td style="border:1px solid #000000;text-align: center;">{{ $mov->tipo_movimiento }}</td>
    </tr>
    @endforeach
</table>

