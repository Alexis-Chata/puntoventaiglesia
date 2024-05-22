<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <link rel=icon href={{ asset('/imagenes/favicon.ico') }}>
    <title>Stocky | Ultimate Inventory With POS</title>
    <link rel="stylesheet" href="{{ asset('css/css_bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('js/jquery-ui-1.13.1/jquery-ui.min.css') }}">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-1.13.1/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    @livewireStyles
    <style>
        .select2-container .select2-selection--single {
            height: 50px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px;
        }

        .select2-container--default .select2-selection--single {
            padding: 0.75rem .75rem;
        }

        ul.ui-autocomplete {
            z-index: 1100;
        }
    </style>
</head>

<body class="text-left">
    <noscript>
        <strong>
            Lo sentimos, pero Stocky no funciona correctamente sin JavaScript habilitado.
            Por favor, activelo para continuar.</strong>
    </noscript>

    <!-- built files will be auto injected -->
    <div class="loading_wrap" id="loading_wrap" style="display: none;">
        <div class="loader_logo">
            <img src="{{ asset('/imagenes/logo.png') }}" class="" alt="logo" />

        </div>

        <div class="loading"></div>
    </div>
    <div id="app">
        {{ $slot }}
    </div>
    @livewireScripts
    <script type="text/javascript">
        window.Livewire.on('enviar_to_imprimir', datos_impresion => {
            imprimir_comprobant_pdf_js(datos_impresion[0][1])
        });

        function imprimir_comprobant_pdf_js(urlPdf)
        {
            const pdfUrl = urlPdf;
            fetch(`http://localhost:3000/print?url=${encodeURIComponent(pdfUrl)}`)
                .then(response => response.text())
                .then(result => {
                    alert(result);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</body>
</html>
