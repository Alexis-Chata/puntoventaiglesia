php
Copy code
<!DOCTYPE html>
<html>
<head>
    <title>Documento PDF</title>
    <style>
        *{
            margin: 0px;
        }

        .style40 .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 45%; /* Ajusta este valor según el ancho deseado para el código de barras */
        }
        .style30 .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 35%;
        }

        .style12 .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 35%;
        }

        .style24 .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 35%;
        }

        .style18 .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 35%;
        }

        .style20 .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 20%;
        }

        .style14 .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 20%;
        }

        .style10 .barcode {
            display: block;
            margin: 0 auto; /* Centra el código de barras horizontalmente */
            width: 20%;
        }

        body {
            margin-left: 2px;
        }
        .row {
            width: 100%; /* Ancho completo para el contenedor */
        }

        .style12 {
            float: left;
            width: 29.3%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 180px; /* Altura mínima para mejor visualización */
        }

        .style40 {
            float: left;
            width: 22%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 100px; /* Altura mínima para mejor visualización */
        }

        .style30 {
            float: left;
            width: 29.3%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 100px; /* Altura mínima para mejor visualización */
        }

        .style24 {
            float: left;
            width: 29.3%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 125px; /* Altura mínima para mejor visualización */
        }

        .style20 {
            float: left;
            width: 44%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 100px; /* Altura mínima para mejor visualización */
        }

        .style14 {
            float: left;
            width: 44%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 120px; /* Altura mínima para mejor visualización */
        }

        .style10 {
            float: left;
            width: 44%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 160px; /* Altura mínima para mejor visualización */
        }

        .style18 {
            float: left;
            width: 29.3%; /* Ajustado para incluir márgenes */
            margin: 1%; /* Margen pequeño para separación */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho */
            border: 1px dotted #ccc;
            padding: 3px; /* Espaciado interno */
            min-height: 135px; /* Altura mínima para mejor visualización */
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="row">
        @php $p = 1;$separador = 0;
            if ($barcode_style_pdf == 'style40') {
                $separador = 4;
            }
            elseif($barcode_style_pdf == 'style12' or $barcode_style_pdf == 'style30' or $barcode_style_pdf == 'style24' or $barcode_style_pdf == 'style18') {
                $separador = 3;
            }

            elseif($barcode_style_pdf == 'style20' or $barcode_style_pdf == 'style14' or $barcode_style_pdf == 'style10') {
                $separador = 2;
            }
        @endphp
        @foreach ($lista_productos as $tey => $lproduc)
                    @for ($i = 0; $i < $lista_productos[$tey]['cantidad']; $i++)
                            <div class="{{$barcode_style_pdf}}">
                                <div class="head_barcode text-left" style="padding-left: 10px; font-weight: bold;">
                                    <span class="barcode-name">{{ $lista_productos[$tey]['nombre'] }}</span><br>
                                    <span class="barcode-price">{{ $configuracion->moneda->simbolo }} {{ $lista_productos[$tey]['precio'] }}</span>
                                </div>
                                <center>
                                <div textmargin="0" fontoptions="bold" class="barcode">
                                     {!! DNS1D::getBarcodeHTML($tey, $lista_productos[$tey]['simbologia'], 1, 37) !!}
                                </div>
                                </center>
                                <div style="font-weight: bold;text-align: center;">
                                    <span class="barcode-name">{{ $tey }}</span><
                                </div>
                            </div>
                        @if ($p%$separador == 0)
                            <div class="clear"></div>
                        @endif
                            @php $p = $p+1; @endphp
                    @endfor
        @endforeach
    </div>
</body>
</html>
