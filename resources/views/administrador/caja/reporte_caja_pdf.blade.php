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
        <h1 class="modal-title fs-5" id="modalReporteCajaLabel">Reporte de Caja</h1>
    </div>
    <div class="modal-body">
        <div class="row mb-3">
                <div class="col-12 my-1">
                    <b>Nombre Cajero :</b> {{ $caja->user->name . ' ' . $caja->user->lastname }}<br>
                    <b> Caja Aperturada : </b> {{$caja->fecha_apertura}}<br>
                    <b> Caja Cierre : </b> {{$caja->fecha_cierre}}<br>
                    <b>Monto Inicial :</b> {{$configuracion->moneda->simbolo}}.{{ $caja->mcajas->first()->monto }}<br>
                    <b>Monto Actual :</b> {{$configuracion->moneda->simbolo}}.{{ $caja->monto }}<br>
                </div>
                <div class="col-12">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Tipo de movimiento</th>
                                <th class="text-center">Signo</th>
                                <th class="text-center">Ingreso</th>
                                <th class="text-center">Egreso</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($caja->mcajas as $mcaja)
                                <tr>
                                    <td class="text-center table-gris">{{$mcaja->tmovmientocaja->name}}</td>
                                    <td class="text-center table-gris">{{$mcaja->signo}}</td>
                                    <td class="text-center table-gris">
                                        @if ($mcaja->signo == '+')
                                        {{$configuracion->moneda->simbolo.$mcaja->monto}}
                                        @else
                                        @endif
                                    </td>
                                    <td class="text-center table-gris">
                                        @if ($mcaja->signo == '-')
                                            {{$configuracion->moneda->simbolo.$mcaja->monto}}
                                        @else
                                        @endif
                                    </td>
                                    <td class="text-center table-dark"></td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td colspan="2" class="table-dark text-center">Total</td>
                                    <td class="table-success text-center">{{$configuracion->moneda->simbolo.$caja->mcajas->where('signo','+')->sum('monto')}}</td>
                                    <td class="table-danger text-center">{{$configuracion->moneda->simbolo.$caja->mcajas->where('signo','-')->sum('monto')}}</td>
                                    <td class="table-info text-center">{{$configuracion->moneda->simbolo.($caja->mcajas->where('signo','+')->sum('monto')-$caja->mcajas->where('signo','-')->sum('monto'))}}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</body>
</html>