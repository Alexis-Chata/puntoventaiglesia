<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante</title>
    <style>
        * {
            font-size: 12px;
            margin-left: 2.5px;
            margin-right: 2.5px;
            margin-top: 2.5px;
        }
    </style>
</head>

<body>
    <table style="width: 100%">
        <tr>
            <td><center><img src="{{ asset($configuracion->logo) }}" alt="" srcset="" width="136px;"></center></td>
        </tr>
        <tr>
            <td style="font-size: 24px;"><center>{{ $configuracion->name }}</center></td>
        </tr>
    </table>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <b>Fecha :</b> {{ $posventa->created_at }}
                </td>
            </tr>
            <tr>
                <td><b>Dirección : </b> {{ $configuracion->direccion }}</td>
            </tr>
            <tr>
                <td>
                    <b> Cliente :</b> {{ $posventa->cliente_name }}
                </td>
            </tr>
            <tr>
                <td>
                    <b> Nit :</b> {{ $posventa->cliente_nit }}
                </td>
            </tr>
            <tr>
                <td>
                    <b> Almacen :</b> {{ $posventa->almacen_name }}
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table" style="width: 100%">
        <tbody>
            @foreach ($posventa->posventadetalles as $detalle)
                <tr>
                    <td colspan="2" style="text-align: left;">{{ strtoupper($detalle->producto_nombre) }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;border-bottom: dashed 1px black;">
                        {{ number_format($detalle->producto_cantidad, 2) }} x
                        {{ number_format($detalle->producto_precio, 2) }} - D {{ number_format($detalle->producto_descuento, 2) }}
                    </td>
                    <td style="text-align: right;border-bottom: dashed 1px black;">
                        {{ number_format($detalle->producto_importe, 2) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align: left;border-bottom: dashed 1px black;" scope="col"><b>Total Previo (+)</b></td>
                <td style="text-align: right;border-bottom: dashed 1px black;"><b>{{ $configuracion->moneda->simbolo }}
                        {{ number_format($posventa->total_pagar_previo, 2) }}</b></td>
            </tr>
            @if ($posventa->impuesto_monto > 0)
                <tr>
                    <td style="text-align: left;border-bottom: dashed 1px black;" scope="col"><b>Impuesto (+)</b>
                    </td>
                    <td style="text-align: right;border-bottom: dashed 1px black;">
                        <b>{{ $configuracion->moneda->simbolo }} {{ number_format($posventa->impuesto_monto, 2) }}</b>
                    </td>
                </tr>
            @endif
            @if ($posventa->descuento_items > 0)
            <tr>
                <td style="text-align: left;border-bottom: dashed 1px black;" scope="col"><b>Descuento Items (-)</b>
                </td>
                <td style="text-align: right;border-bottom: dashed 1px black;">
                    <b>{{ $configuracion->moneda->simbolo }} {{ number_format($posventa->descuento_items, 2) }}</b>
                </td>
            </tr>
            @endif
            @if ($posventa->descuento > 0)
                <tr>
                    <td style="text-align: left;border-bottom: dashed 1px black;" scope="col"><b>Descuento (-)</b>
                    </td>
                    <td style="text-align: right;border-bottom: dashed 1px black;">
                        <b>{{ $configuracion->moneda->simbolo }} {{ number_format($posventa->descuento, 2) }}</b>
                    </td>
                </tr>
            @endif
            @if ($posventa->envio > 0)
                <tr>
                    <td style="text-align: left;border-bottom: dashed 1px black;" scope="col"><b>Envio(+)</b></td>
                    <td style="text-align: right;border-bottom: dashed 1px black;">
                        <b>{{ $configuracion->moneda->simbolo }} {{ number_format($posventa->envio, 2) }}</b>
                    </td>
                </tr>
            @endif
            <tr>
                <td style="text-align: left;border-bottom: dashed 1px black;" scope="col"><b>Total (+)</b></td>
                <td style="text-align: right;border-bottom: dashed 1px black;"><b>{{ $configuracion->moneda->simbolo }}
                        {{ number_format($posventa->total_pagar, 2) }}</b></td>
            </tr>
            <tr>
                <td style="text-align: left;border-bottom: dashed 1px black;" scope="col"><b>Pagado</b></td>
                <td style="text-align: right;border-bottom: dashed 1px black;"><b>{{ $configuracion->moneda->simbolo }}
                        {{ number_format($posventa->monto_pago, 2) }}</b></td>
            </tr>
            @if ($posventa->monto_pendiente > 0)
            <tr>
                <td style="text-align: left;border-bottom: dashed 1px black;" scope="col"><b>Debido</b></td>
                <td style="text-align: right;border-bottom: dashed 1px black;"><b>{{ $configuracion->moneda->simbolo }}
                        {{ number_format($posventa->monto_pendiente, 2) }}</b></td>
            </tr>
            @endif

        </tbody>
    </table>
    <table class="table" style="width: 100%">
        <thead class="table-light">
            <tr>
                <th scope="col" style="text-align: left;border-bottom: dashed 1px black;">
                    <b>Al:</b>
                </th>
                <th scope="col"
                    style="text-align: center;border-bottom: dashed 1px black;"><b>Monto:</b>
                </th>
                <th scope="col"
                    style="text-align: right;border-bottom: dashed 1px black;">
                    <b>Cambiar:</b>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left">Contado</td>
                <td style="text-align: center">{{ number_format($posventa->cantidad_recibida, 2) }}</td>
                <td style="text-align: right">{{ number_format($posventa->cambio, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%">
        <tr>
            <td style="text-align: center;">
                <b>Gracias Por Su Compra, Vuelva Pronto.</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <center><b>SL_{{ $posventa->id }}</b></center>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td style="width :16%;"></td>
            <td>{!! DNS1D::getBarcodeHTML('SL_' . $posventa->id, 'C128') !!}</td>
            <td style="width :16%;"></td>
        </tr>
    </table>
</body>
</html>
