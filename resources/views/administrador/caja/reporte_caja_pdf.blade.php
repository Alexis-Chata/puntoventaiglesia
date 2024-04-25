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
        .table-warning{background-color: #ffe484;}
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
    <div class="modal-body">
        <div class="row mb-3" style="display: flex;">
            <div class="col-12">
                <table width='100%'>
                    <tr>
                        <td>
                            <h1 class="modal-title fs-5" id="modalReporteCajaLabel">Reporte de Caja</h1>
                            <b>Nombre Cajero :</b> {{ $caja->user->name . ' ' . $caja->user->lastname }}<br>
                            <b>Caja Aperturada : </b> {{$caja->fecha_apertura}}<br>
                            <b>Caja Cierre : </b> {{$caja->fecha_cierre}}<br>
                            <b>Monto Inicial :</b> {{$configuracion->moneda->simbolo}}.{{ $caja->mcajas->first()->monto }}<br>
                            <b>Monto Actual :</b> {{$configuracion->moneda->simbolo}}.{{ $caja->monto }}<br></td>
                        <td><img src="{{asset($configuracion->logo)}}" alt="" width="128px" height="128px"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mb-3">
                <div class="col-12">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Tipo de movimiento</th>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">Signo</th>
                                <th class="text-center">Deuda</th>
                                <th class="text-center">Ingreso</th>
                                <th class="text-center">Egreso</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @php $total_deuda_mov_caja = 0;  @endphp
                            @foreach ($caja->mcajas as $mcaja)
                                <tr>
                                    <td class="text-center table-gris">{{$mcaja->tmovmientocaja->name}}</td>
                                    <td class="text-center table-gris"> @if ($mcaja->tmovimiento_caja_id == 3) {{$mcaja->m_cajable->cliente_name}} @else - @endif</td>
                                    <td class="text-center table-gris">{{$mcaja->signo}}</td>
                                    <td class="text-center table-gris">
                                        @if ($mcaja->signo == '+' && $mcaja->m_cajable_type == 'App\Models\Posventa')
                                        @php
                                            $total_deuda_mov_caja = $total_deuda_mov_caja+$mcaja->m_cajable->monto_pendiente;
                                        @endphp
                                            {{ $configuracion->moneda->simbolo . $mcaja->m_cajable->monto_pendiente }}
                                        @else
                                        @endif
                                    </td>
                                    <td class="text-center table-gris">
                                        @if ($mcaja->signo == '+')
                                        {{$configuracion->moneda->simbolo.".".$mcaja->monto}}
                                        @else
                                        Q.0
                                        @endif
                                    </td>
                                    <td class="text-center table-gris">
                                        @if ($mcaja->signo == '-')
                                            {{$configuracion->moneda->simbolo.".".$mcaja->monto}}
                                        @else
                                        Q.0
                                        @endif
                                    </td>
                                    <td class="text-center table-warning">
                                        @php
                                            if ($mcaja->signo == '+') {$total = $total + $mcaja->monto;}
                                            elseif($mcaja->signo == '-') {$total = $total - $mcaja->monto;}
                                        @endphp
                                            {{$configuracion->moneda->simbolo.".".$total}}
                                    </td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td colspan="3" class="table-dark text-center">Total</td>
                                    <td class="table-success text-center">
                                        {{  $configuracion->moneda->simbolo . $total_deuda_mov_caja }}
                                    </td>
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
