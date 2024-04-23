<div>
    <div>
        <div class="row">
            <div class="col-12 fs-6">
                <span class="fs-3 fw-bold">Imprimir código de barras</span> Productos | Imprimir código de barras
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <label for="seleccionar_almacen">Almacén <span>*</span></label>
                <select id="seleccionar_almacen" class="form-select" wire:model.live='salmacen'>
                    <option value="">Elegir</option>
                    @foreach ($almacenes as $almacen)
                    <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-12">
                <input type="hidden" id="buscar_producto_oculto" wire:model.live='buscar_producto_oculto'>
            </div>
            <div class="col-12">
                <label class="visually-hidden" for="buscar_producto">Nombre del Producto</label>
                <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-search"></i></div>
                    <input type="text" class="form-control" id="buscar_producto" autocomplete="off"
                        placeholder="Buscar Producto" wire:model.live.debounce.500ms='search'>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Nombre del producto</th>
                            <th class="text-center">Código del producto</th>
                            <th class="text-center">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="table-secondary">
                        @foreach ($lista_productos as $key => $list_product)
                        @php
                            $nombre = 'lista_productos.'.$key.'.cantidad';
                        @endphp
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">{{$lista_productos[$key]['nombre']}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{$key}}</td>
                            <td class="text-center" style="vertical-align: middle;width:120px;">
                                <input type="number" style="" class="form-control text-center" min='0' wire:model.live='{{$nombre}}'>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-center">Datos no disponibles</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <label for="seleccionar_almacen">Tamaño de Papel</label>
                <select id="seleccionar_almacen" class="form-select" wire:model.live = 'stipo_papel'>
                    <option value="">Tamaño del Papel</option>
                    <option value="1">40 per sheet (a4) (1.799 * 1.003)</option>
                    <option value="2">30 per sheet (2.625 * 1)</option>
                    <option value="3">24 per sheet (a4) (2.48 * 1.334)</option>
                    <option value="4">20 per sheet (4 * 1)</option>
                    <option value="5">18 per sheet (a4) (2.5 * 1.835)</option>
                    <option value="6">14 per sheet (4 * 1.33)</option>
                    <option value="7">12 per sheet (a4) (2.5 * 2.834)</option>
                    <option value="8">10 per sheet (4 * 2)</option>
                </select>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-12">
                <button class="btn btn-primary"><i class="fas fa-edit"></i> Actualizar</button>
                <button class="btn btn-danger" wire:click='reiniciar_lista_productos'><i class="fas fa-sync"></i> Reiniciar</button>
                <button class="btn btn-secondary" wire:loading.attr="disabled" wire:target="descargar_codigo_barrar_imprimir" wire:click='descargar_codigo_barrar_imprimir'>
                    <i class="fas fa-print"></i> Impresión
                </button>
            </div>
        </div>
        @if ($stipo_papel <> '' && count($lista_productos) > 0)
        <div class="row my-2">
            <div class="col-md-12">
                <div class="{{$barcode}}">
                    @foreach ($lista_productos as $tey => $lproduc)
                        @for ($i = 0; $i < $lista_productos[$tey]['cantidad']; $i++)
                            <div class="{{$barcode_style}}">
                                <div class="head_barcode text-left" style="padding-left: 10px; font-weight: bold;">
                                    <span class="barcode-name">{{$lista_productos[$tey]['nombre']}}</span>
                                    <span class="barcode-price">{{ $configuracion->moneda->simbolo }} {{$lista_productos[$tey]['precio']}}</span>
                                </div>
                                <div textmargin="0" fontoptions="bold" class="barcode">
                                   <center> {!! DNS1D::getBarcodeHTML($tey,$lista_productos[$tey]['simbologia'],1,37) !!}</center>
                                </div>
                                <div style="font-weight: bold;text-align: center;">
                                    <span class="barcode-name">{{ $tey }}</span>
                                </div>
                            </div>
                        @endfor
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
