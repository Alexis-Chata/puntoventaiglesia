<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            margin-left: 2px;
            margin-top: 2px;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }


        .barcodea4 {
            display: block;
            page-break-after: always;
            height: 11.6in;
            padding: 0.1in 0 0 0.1in;
            width: 8.25in;
        }

        .barcode_non_a4 {
            height: 10.3in;
            padding-top: 0.1in;
            width: 8.45in;
        }

        .barcode_non_a4,
        .barcodea4 {
            display: block;
            page-break-after: always;
        }

        .barcode_non_a4 .barcode-name,
        .barcodea4 .barcode-name {
            display: block;
        }

        .barcodea4 .barcode-item {
            border: 1px dotted #ccc;
            display: block;
            float: left;
            font-size: 12px;
            line-height: 14px;
            overflow: hidden;
            text-align: center;
            text-transform: uppercase;
        }

        .barcode_non_a4 .barcode-item {
            border: 1px dotted #ccc;
            display: block;
            float: left;
            font-size: 12px;
            line-height: 14px;
            overflow: hidden;
            text-align: center;
            text-transform: uppercase;
        }

        .barcodea4 .style40 {
            height: 1.003in;
            margin: 0 0.07in;
            padding-top: 0.05in;
            width: 1.799in;
        }

        .barcode_non_a4 .style30 {
            height: 1in;
            margin: 0 0.07in;
            padding-top: 0.05in;
            width: 2.625in;
        }

        .barcodea4 .style24 {
            height: 1.335in;
            margin-left: 0.079in;
            padding-top: 0.05in;
            width: 2.48in;
        }

        .barcode_non_a4 .style20 {
            height: 1in;
            margin: 0 0.1in;
            padding-top: 0.05in;
            width: 4in;
        }

        .barcodea4 .style18 {
            font-size: 13px;
            height: 1.835in;
            line-height: 20px;
            margin-left: 0.079in;
            padding-top: 0.05in;
            width: 2.5in;
        }

        .barcode_non_a4 .style14 {
            height: 1.33in;
            margin: 0 0.1in;
            padding-top: 0.1in;
            width: 4in;
        }

        .barcodea4 .style12 {
            font-size: 14px;
            height: 2.834in;
            line-height: 20px;
            margin-left: 0.079in;
            padding-top: 0.05in;
            width: 2.5in;
        }

        .barcode_non_a4 .style10 {
            font-size: 14px;
            height: 2in;
            line-height: 20px;
            margin: 0 0.1in;
            padding-top: 0.1in;
            width: 4in;
        }

        .my-2 {
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        .col-md-12 {
            flex: 0 0 auto;
            width: 100%;
        }

        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1* var(--bs-gutter-y));
            margin-right: calc(-.5* var(--bs-gutter-x));
            margin-left: calc(-.5* var(--bs-gutter-x));
        }
    </style>
</head>

<body>
    <div class="row" style="display: flex;">
        <div class="col-md-12">
            <div class="{{ $barcode }}">
                @forelse ($lista_productos as $tey => $lproduc)
                    @for ($i = 0; $i < $lista_productos[$tey]['cantidad']; $i++)
                        <div class="{{ $barcode_style }}">
                            <div class="head_barcode text-left" style="padding-left: 10px; font-weight: bold;">
                                <span class="barcode-name">{{ $lista_productos[$tey]['nombre'] }}</span>
                                <span class="barcode-price">{{ $configuracion->moneda->simbolo }} {{ $lista_productos[$tey]['precio'] }}</span>
                            </div>
                            <div textmargin="0" fontoptions="bold" class="barcode">
                                <center> {!! DNS1D::getBarcodeHTML($tey, $lista_productos[$tey]['simbologia'], 1, 37) !!}</center>
                            </div>
                        </div>
                    @endfor
                @empty

                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
