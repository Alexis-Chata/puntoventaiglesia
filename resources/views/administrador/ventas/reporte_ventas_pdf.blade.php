<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ventas</title>
    <link href="{{asset('css/estilo_reporte.css')}}" rel="stylesheet" >
</head>
<body class="m-1 fs-12">
    <div>
        <div class="col-12">
            <table>
                <tr>
                    <td>
                        <h1 class="modal-title fs-5" id="modalReporteCajaLabel">Reporte de Ventas</h1><br>
                        <b>Compras en total : {{$posventas->count()}}</b><br>
                        <b>Fecha Inicio :</b> {{$posventas->first()->created_at}}<br>
                        <b>Fecha Final :</b>  {{$posventas->sortByDesc('fecha')->first()->created_at}}
                    </td>
                    <td>
                        <img src="{{asset($configuracion->logo)}}" alt="" width="128px">
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div>
        <table class="table table-hover">
            <thead class="table-light">
                <tr class="text-center">
                    <th class="encabezado-dark" style="width:100px;">Fecha</th>
                    <th class="encabezado-dark" style="width:100px;">Almacen</th>
                    <th class="encabezado-dark" style="width:100px;">Comprobante</th>
                    <th class="encabezado-dark" style="width:100px;">Cliente</th>
                    <th class="encabezado-dark" style="width:100px;">Impuesto Porcentaje</th>
                    <th class="encabezado-dark" style="width:100px;">Impuesto</th>
                    <th class="encabezado-dark" style="width:100px;">Descuento</th>
                    <th class="encabezado-dark" style="width:100px;">Envio</th>
                    <th class="encabezado-dark" style="width:100px;">Total a Pagar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posventas as $pventa)
                <tr>
                    <td class="encabezado-body">{{ $pventa->created_at }}</td>
                    <td class="encabezado-body">{{ $pventa->almacen_name }}</td>
                    <td class="encabezado-body">{{ "SL_".$pventa->id }}</td>
                    <td class="encabezado-body">{{ $pventa->cliente_name }}</td>
                    <td class="encabezado-body">{{ $pventa->impuesto_porcentaje }}%</td>
                    <td class="encabezado-body">{{ $configuracion->moneda->simbolo.$pventa->impuesto_monto }}</td>
                    <td class="encabezado-body">{{ $configuracion->moneda->simbolo.$pventa->descuento }}</td>
                    <td class="encabezado-body">{{ $configuracion->moneda->simbolo.$pventa->envio }}</td>
                    <td class="encabezado-body">{{ $configuracion->moneda->simbolo.$pventa->total_pagar }}</td>
                </tr>
                @empty
                @endforelse
                    <tr>
                        <td colspan="5" style="background-color: black;color:white;text-align:center;">
                            Total
                        </td>
                        <td class="text-center table-success" style="text-align: center;">{{$configuracion->moneda->simbolo.$posventas->sum('impuesto_monto')}}</td>
                        <td class="text-center table-success" style="text-align: center;">{{$configuracion->moneda->simbolo.$posventas->sum('descuento')}}</td>
                        <td class="text-center table-success" style="text-align: center;">{{$configuracion->moneda->simbolo.$posventas->sum('envio')}}</td>
                        <td class="text-center table-success" style="text-align: center;">{{$configuracion->moneda->simbolo.$posventas->sum('total_pagar')}}</td>
                    </tr>
            </tbody>
        </table>
    </div>
    <div>
        <table class="table table-hover">
            <thead class="table-light">
                <tr class="text-center">
                    <th  class="encabezado-dark" style="width:100px;" colspan="6">Devoluciones</th>
                </tr>
                <tr class="text-center">
                    <th class="encabezado-dark" style="width:100px;">Fecha Venta</th>
                    <th class="encabezado-dark" style="width:100px;">Fecha Devolución</th>
                    <th class="encabezado-dark" style="width:100px;">Recibo de Venta</th>
                    <th class="encabezado-dark" style="width:100px;">Almacen</th>
                    <th class="encabezado-dark" style="width:100px;">Cliente</th>
                    <th class="encabezado-dark" style="width:100px;">Total a Pagar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posventas as $pventa)
                    @foreach ($pventa->devolucions as $dev)
                        <tr>
                            <td class="encabezado-body">{{ $pventa->created_at }}</td>
                            <td class="encabezado-body">{{ $dev->fecha }}</td>
                            <td class="encabezado-body">{{ "SL_".$pventa->id }}</td>
                            <td class="encabezado-body">{{ $dev->almacen_name }}</td>
                            <td class="encabezado-body">{{ $dev->cliente_name }}</td>
                            <td class="encabezado-body">{{ $dev->monto_pago }}</td>
                        </tr>
                    @endforeach
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
