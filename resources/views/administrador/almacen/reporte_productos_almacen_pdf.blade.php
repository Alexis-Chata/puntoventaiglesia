<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Caja</title>
    <style>
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .table-success{background-color: #bcd0c7;}

        .table-danger{background-color: #f8d7da;}

        .table-warning{background-color: #fff3cd;}

        .table-info{background-color: #cff4fc;}

        .table-gris{background-color: #0000000d;}

        .table-dark{
            background-color: black;
            color: white;
        }

        table {
            caption-side: bottom;
            border-collapse: collapse;
            width: 100%;
            padding: .5rem .5rem;
        }
    </style>
</head>
<body>
    <div class="modal-header">
        <center><h1 class="modal-title fs-5" id="modalReporteCajaLabel">Reporte de Almacen</h1><center>
        <center><img src="{{ asset($configuracion->logo) }}" alt="" srcset="" width="64px;"></center>
        <center><h3 class="modal-title fs-5" id="modalReporteCajaLabel">{{$configuracion->name}}</h3><center>
    </div>
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-12">
                <table>
                    <thead>
                        <tr>
                            <th style="background-color: black;color:white;width:240px;text-align:center;border: solid 1px black">Tipo de Producto</th>
                            <th style="background-color: black;color:white;width:180px;text-align:center;border: solid 1px black">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td style="text-align:center;border: solid 1px black">Estandar</td>
                                <td style="text-align:center;border: solid 1px black">{{ $bproductos->where('tipo','estandar')->count() }}</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;border: solid 1px black">Compuestos</td>
                                <td style="text-align:center;border: solid 1px black">{{ $bproductos->where('tipo','compuesto')->count() }}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <table>
                    <thead>
                        <tr>
                            <th style="background-color: black;color:white;width:240px;text-align:center;border: solid 1px black">Producto</th>
                            <th style="background-color: black;color:white;width:180px;text-align:center;border: solid 1px black">Almacen</th>
                            <th style="background-color: black;color:white;width:120px;text-align:center;border: solid 1px black">Stock</th>
                            <th style="background-color: black;color:white;width:120px;text-align:center;border: solid 1px black">Stock Limite</th>
                            <th style="background-color: black;color:white;width:150px;text-align:center;border: solid 1px black">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos_almacen as $palmacen)
                            <tr>
                                <td style="text-align:center;border: solid 1px black">{{ $palmacen->producto->designacion }}</td>
                                <td style="text-align:center;border: solid 1px black">{{ $palmacen->almacen->nombre }}</td>
                                <td style="text-align:center;border: solid 1px black">{{ $palmacen->stock }}</td>
                                <td style="text-align:center;border: solid 1px black">{{ $palmacen->producto->alerta_stock }}</td>

                                    @if ($palmacen->stock == 0)
                                        <td class="table-danger"  style="text-align:center;border: solid 1px black">
                                        <span class="badge text-bg-danger">Insuficiente</span>
                                        </td>
                                    @elseif (
                                    $palmacen->stock > 0
                                            &&
                                    $palmacen->stock <=2
                                    )
                                    <td class="table-warning text-center" style="text-align:center;border: solid 1px black">
                                        <span class="badge text-bg-warning">Por Acabar</span>
                                    <t/d>
                                    @elseif ($palmacen->stock >= 3 && $palmacen->stock <= $palmacen->producto->alerta_stock)
                                    <td class="table-success text-center">
                                        <span class="badge text-bg-success">Suficiente</span>
                                    </td>
                                    @elseif ($palmacen->stock > $palmacen->producto->alerta_stock )
                                    <td class="table-info text-center">
                                    <span class="badge text-bg-info">Exceso</span>
                                    </td>
                                    @endif
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>




