php
Copy code
<!DOCTYPE html>
<html>
<head>
    <title>Documento PDF</title>
    <style>
        .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 50%; /* Ajusta este valor según el ancho deseado para el código de barras */
        }

        *{
            margin: 0px;
        }
        body {
            margin-left: 2px;
        }
        .row {
            width: 100%; /* Ancho completo para el contenedor */
        }

        .column {
            float: left;
            width: 22%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 100px; /* Altura mínima para mejor visualización */
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="row">
        @foreach ($lista_productos as $tey => $lproduc)
                    @php $p = 1;@endphp
                    @for ($i = 0; $i < $lista_productos[$tey]['cantidad']; $i++)
                            <div class="column">
                                <div class="head_barcode text-left" style="padding-left: 10px; font-weight: bold;">
                                    <span class="barcode-name">{{ $lista_productos[$tey]['nombre'] }}</span>
                                    <span class="barcode-price">{{ $configuracion->moneda->simbolo }} {{ $lista_productos[$tey]['precio'] }}</span>
                                </div>
                                <div textmargin="0" fontoptions="bold" class="barcode">
                                    <center> {!! DNS1D::getBarcodeHTML($tey, $lista_productos[$tey]['simbologia'], 1, 37) !!}</center>
                                </div>
                            </div>
                        @if ($p%4 == 0)
                            <div class="clear"></div>
                        @endif
                            @php $p = $p+1; @endphp
                    @endfor
        @endforeach
    </div>
</body>
</html>
