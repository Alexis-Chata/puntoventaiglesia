<div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">
            <div class="card text-center">
                <div class="card-header">
                    <div class="row justify-content-between" style="text-align: right;">
                        <div class="col-auto">
                            <a href="{{ route('admin.index') }}">
                                <img src="{{ asset($configuracion->logo) }}" alt="" width="64px;">
                            </a>
                        </div>
                        <div class="row col-auto align-items-center">
                            <div class="col-auto" style="vertical-align: middle;">
                                @if ($cajero->modo == 1)
                                <button role="button" class="btn btn-secondary" wire:loading.attr="disabled" wire:target="cambiar_modo_usuario" wire:click='cambiar_modo_usuario'>
                                    <i class="bi bi-laptop" id="modo_laptop" ></i>
                                </button>
                                @elseif($cajero->modo == 2)
                                <button role="button" class="btn btn-secondary" wire:loading.attr="disabled" wire:target="cambiar_modo_usuario"  wire:click='cambiar_modo_usuario'>
                                    <i class="bi bi-tablet" id="modo_tablet"></i>
                                </button>
                                @endif
                            </div>
                            <div class="col-auto" style="vertical-align: middle;">
                                <button role="button" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#modalReporteCaja"><i class="bi bi-book-fill"></i></button>
                            </div>
                            <div class="col-auto" style="vertical-align: middle;">
                                <button role="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#modalGasto"><i class="bi bi-bookmark-dash-fill"></i></button>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('logout') }}" method="post">
                                    <button class="btn btn-danger">Cerrar Sesion</button>
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($cajero->cajas->where('fecha_cierre', false)->count() == 0)
                            <div class="col-12 my-1">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#modalCaja">
                                    Aperturar Caja <i class="fas fa-box"></i>
                                </button>
                            </div>
                        @else
                            <div class="col-12 my-1">
                                Nombre Cajero : <b>{{ $cajero->name . ' ' . $cajero->lastname }}</b><br>
                                Caja Aperturada : <b>
                                    {{ $cajero->cajas->where('fecha_cierre', false)->first()->fecha_apertura }} </b><br>
                                @if ($cajero->cajas->where('fecha_cierre', false)->first()->mcajas->first())
                                    Monto Inicial :
                                    <b>{{ $configuracion->moneda->simbolo . $cajero->cajas->where('fecha_cierre', false)->first()->mcajas->first()->monto }}</b>
                                    <br>
                                    Monto Actual :
                                    <b>{{ $configuracion->moneda->simbolo . $cajero->cajas->where('fecha_cierre', false)->first()->monto }}</b>
                                    <br>
                                    <button class="btn btn-success"
                                        wire:click="cerrar_caja('{{ $cajero->cajas->where('fecha_cierre', false)->first()->id }}')"
                                        wire:confirm="¿Esta Seguro que Desea Cerrar Caja?">Cerrar Caja</button>
                                @else
                                    Monto Inicial : <b>{{$configuracion->moneda->simbolo}} 0</b>
                                @endif
                            </div>
                        @endif
                        <div class="col-12">
                            <input type="hidden" id="buscar_cliente_oculto2" wire:model.live="bclienteoculto">
                        </div>
                        <div class="col-12 my-1">
                            <div class="input-group">
                                <input type="text" class="form-control" id="buscar_cliente2" autocomplete="off"
                                    placeholder="Escribir Usuario" wire:model.live="bcliente">
                                <div class="input-group-text" data-bs-toggle="modal" data-bs-target="#modalCliente"
                                    wire:click='modal_cliente'>
                                    <i class="bi bi-person-add"></i> <span class="text-danger">*</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 my-1">
                            <select class="form-select" id="compra_almacen" wire:model.live="almacen_id">
                                <option value="">Elegir</option>
                                @forelse ($cajero->almacensuser as $alm)
                                    <option value="{{ $alm->almacen->id }}">{{ $alm->almacen->nombre }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-12 my-1">
                            <div class="input-group">
                                <input type="text" class="form-control"  autocomplete="off"
                                    placeholder="Escribir Impresora" wire:model.live="simpresora">
                                <div class="input-group-text" >
                                    <i class="bi bi-printer"></i> <span class="text-danger">*</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 my-1">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 my-1 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="table-success">
                                        <th>Nombre del Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Imp</th>
                                        <th>Des</th>
                                        <th>Total Parcial</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $key => $item)
                                        <tr class="align-middle">
                                            <td>
                                                {{ $item['codigo'] }}
                                                <br>
                                                <span class="badge text-bg-success">{{ $item['designacion'] }}</span>
                                                <i style="color:green;" class="bi bi-pencil-square"></i>
                                            </td>
                                            <td>{{ $configuracion->moneda->simbolo . $item['precio'] }}</td>
                                            <td>
                                                @php $valor_cantidad = 'items.'.$key.'.cantidad';@endphp
                                                <center><input type="number" id="item-{{$key}}-cantidad" step="0.01" class="form-control text-center"
                                                        style="width: 80px;" min=1
                                                        wire:model.live.debounce.500ms='{{ $valor_cantidad }}'>
                                                </center>
                                            </td>
                                            <td>
                                                {{ $configuracion->moneda->simbolo . $item['importe_previo'] }}
                                            </td>
                                            <td>
                                                @php $valor_producto_descuento = 'items.'.$key.'.descuento';@endphp
                                                <center><input type="number" id="item-{{$key}}-descuento" step="0.01" class="form-control text-center"
                                                        style="width: 80px;" min=1
                                                        wire:model.live.debounce.500ms='{{ $valor_producto_descuento }}'>
                                                </center>
                                            </td>
                                            <td>{{ $configuracion->moneda->simbolo . $item['importe'] }}</td>
                                            <td><i style="color:red;font-size: 24px;" class="bi bi-x-circle"
                                                    role="button"
                                                    wire:click="eliminaritem('{{ $key }}')"></i>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                Datos no Disponibles
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 my-1">
                            <div class="row">
                                <div class="col-sm-4 col-12">
                                    <label for="impuesto" class="form-label"><b>Impuesto</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="bi bi-percent"></i></div>
                                        <input type="number" class="form-control" min=0 id="impuesto" placeholder="0"
                                            wire:model.live="impuesto_porcentaje">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <label for="descuento" class="form-label"><b>Descuento</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text">{{ $configuracion->moneda->simbolo }}</div>
                                        <input type="number" class="form-control" min=0 id="descuento"
                                            placeholder="0" wire:model.live="descuento">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <label for="envio" class="form-label"><b>Envió</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text">{{ $configuracion->moneda->simbolo }} </div>
                                        <input type="number" class="form-control" min=0 id="envio"
                                            placeholder="0" wire:model.live="envio">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-12 col-sm-6">
                            <button class="btn btn-success btn-lg" wire:click="reiniciar">Reiniciar</button>
                        </div>
                        <div class="col-12 col-sm-6">
                            @if ($cajero->cajas->where('fecha_cierre', false)->count() == 0)
                                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#modalCaja">
                                    Aperturar Caja <i class="fas fa-box"></i>
                                </button>
                            @else
                                <button @if (count($items) == 0 or $bclienteoculto == false or $bcliente == false) disabled @endif class="btn btn-danger btn-lg"
                                    data-bs-toggle="modal" data-bs-target="#agregarPagoPosModal">Pagar Ahora</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-7">
            <div class="card">
                <div class="card-body">
                    @if ($seleccionar_almacen)
                        <div class="row my-2">
                            <div class="col-12 col-sm-6 my-1">
                                <label for="categoria"><b>Categoria</b></label>
                                <select class="form-select" id="categoria" wire:model.live="categoria_id">
                                    <option value="">Todos</option>
                                    @forelse ($categorias as $categoria)
                                        <option value="{{ $categoria->cat_cod }}">{{ $categoria->name }}</option>
                                    @empty
                                        <option value="">Sin Categorias</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 my-1">
                                <label for="marca"><b>Marcas</b></label>

                                <select class="form-select" id="marca" wire:model.live="marca_id">
                                    <option value="">Todos</option>

                                    @forelse ($marcas as $marca)
                                        @if (isset($marca->id))
                                        <option value="{{ $marca->id }}">{{ $marca->name }}</option>
                                        @endif
                                    @empty
                                        <option value="">Sin Marcas</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-12 my-2">
                                <label class="visually-hidden" for="buscar_producto">Buscar Producto</label>
                                <div class="input-group">
                                    <div class="input-group-text"><i class="bi bi-search"></i></div>
                                    <input type="text" class="form-control" id="buscar_producto" wire:model.live='buscar_producto'
                                        placeholder="Buscar Producto" autofocus>
                                </div>
                            </div>
                        </div>
                        <!--lista de producto-->
                        <div class="row my-2">
                            @forelse ($productos as $product)
                                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-3 my-2" role="button" wire:key="{{ $product->producto->id }}"
                                    wire:click="agregaritem('{{ $product->producto->id }}')">
                                    <div class="card">
                                        <img src="{{ asset($product->producto->imagen) }}" style="object-fit: contain;"
                                            height="80px;" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 m-0 p-0" style="padding: 0px;">
                                                    {{ $product->producto->designacion }}<br>
                                                    {{ $product->producto->codigo }}<br>
                                                    <span class="badge text-bg-warning">
                                                        {{ $configuracion->moneda->simbolo . number_format($product->producto->precio, 2) }}
                                                    </span>
                                                    <span class="badge text-bg-info">
                                                        {{ $product->producto->cunidad->name_cor . ' ' . $product->stock }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                            @endforelse
                        </div>
                        <!--paginacion-->
                        <div class="row my-2">
                            <div class="col-12 text-center table-responsive">
                                {{$productos->links()}}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('administrador.ventas.parts.modal_caja')
    @include('livewire.modal.pos-modal')
    @include('administrador.gastos.parts.gasto-modal')
    @include('administrador.ventas.parts.modal_reporte_caja')
    @include('administrador.personas.parts.cliente-modal')
</div>
@script
    <script>
        $wire.on('dirigir_cursor', () => {
            $("#buscar_producto").focus();
        });

        $wire.on('avertencia_stock', () => {
            alert('Falta Stock');
        });

        $wire.on('cerrar_modal_caja', reservacion => {
            ventana = document.getElementById('cerrar_modal_caja_x').click();
        });

        $wire.on('cerrar_modal_postventa', reservacion => {
            ventana = document.getElementById('cerrar_modal_postventa_x').click();
        });

        $wire.on('cerrar_modal_gasto', reservacion => {
            ventana = document.getElementById('cerrar_modal_gasto_x').click();
        });

        $wire.on('advertencia_almacen', () => {
            Swal.fire({
                position: "center-center",
                icon: "warning",
                title: "Elegir un Almacen para realizar la compra",
                showConfirmButton: false,
                timer: 1500
            });
            ventana = document.getElementById('cerrar_modal_postventa_x').click();
        });

        $wire.on('activar_buscador_cliente', () => {
            $('#buscar_cliente2').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '{{ route('search.buscar_cliente') }}',
                        dataType: 'json',
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data)
                        }
                    });
                },
                minLength: 3,
                select: function(event, ui) {
                    setTimeout(() => {
                        $('#buscar_cliente_oculto2').val('');
                        $('#buscar_cliente_oculto2').val(ui.item.id);
                        $('#buscar_cliente_oculto2')[0].dispatchEvent(new Event('input'));
                        $('#buscar_cliente2').val(ui.item.name);
                    }, 750);
                }
            });
        });

        $wire.on('advertencia_eliminar_venta', () => {

            (async () => {
                const {
                    value: password
                } = await Swal.fire({
                    title: "Enter your password",
                    input: "password",
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    inputLabel: "Password",
                    inputPlaceholder: "Enter your password",
                    inputAttributes: {
                        maxlength: "10",
                        autocapitalize: "off",
                        autocorrect: "off"
                    }
                });
                if (password) {
                    @this.dispatch('eliminar_pos_venta', {
                        password_id: password
                    });
                }
            })()
        })

        $wire.on('advertencia_eliminar_gasto', () => {

            (async () => {
                const {
                    value: password
                } = await Swal.fire({
                    title: "Enter your password",
                    input: "password",
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    inputLabel: "Password",
                    inputPlaceholder: "Enter your password",
                    inputAttributes: {
                        maxlength: "10",
                        autocapitalize: "off",
                        autocorrect: "off"
                    }
                });
                if (password) {
                    @this.dispatch('eliminar_gasto_venta', {
                        password_id: password
                    });
                }
            })()
            })

        $wire.on('mensaje_error_autorización', () => {
                Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Contraseña Incorrecta',
                showConfirmButton: false,
                timer: 2400
                })
            })


        $wire.on('advertencia_almacen', () => {
            Swal.fire({
                title: "Good job!",
                text: "You clicked the button!",
                icon: "success"
            });
        });
    </script>
@endscript
