<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#modalCompra">
    Modal Compras
</button>

<!-- Modal -->
<div wire:ignore.self class="modal fade" id="modalCompra" tabindex="-1" aria-labelledby="modalCompraLabel" aria-hidden="true" >
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-12 fs-6">
                        <span class="fs-3 fw-bold">{{ $titlemodal }} Compras</span> Compras | {{ $titlemodal }} Compras
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="cerrar_modal_compra_x"></button>
            </div>
            <div class="modal-body">
                    <!--fecha - proveedor - almacen-->
                    <div class="row">
                        <div class="col-sm-4 col-12">
                            <label for="compra_fecha" class="form-label">Fecha <span class='text-red'>(*)</span></label>
                            <input type="date" id="compra_fecha" class="form-control" wire:model.live='comprasform.fecha'>
                            @error('comprasform.fecha')<div class="p-1" style="color:red;"> {{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4 col-12">
                                <label for="compra_proveedor" class="form-label">Proveedor <span class='text-red'>(*)</span></label>
                                <input type="hidden" id="buscar_proveedor_oculto" wire:model.live='buscar_proveedor_oculto'>
                                <input type="text" class="form-control" id="buscar_proveedor" wire:model.live='buscar_proveedor' placeholder="Buscar Proveedor"
                                 wire:model.live.debounce.500ms='buscar_proveedor' autocomplete="off">
                            @error('comprasform.prove')<div class="p-1" style="color:red;"> {{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4 col-12">
                            <label for="compra_almacen" class="form-label">Almacen <span class='text-red'>(*)</span></label>
                            <select class="form-select" id="compra_almacen" wire:model.live='comprasform.almacen'>
                                <option value="">Elegir</option>
                                @foreach ($almacens as $alme)
                                <option value="{{$alme->id}}">{{$alme->nombre}}</option>
                                @endforeach
                            </select>
                            @error('comprasform.almacen')<div class="p-1" style="color:red;"> {{ $message }}</div>@enderror
                        </div>
                    </div>
                    <!--buscar modal-->
                    <div class="row my-4">
                        <div class="col-12">
                            <input type="hidden" id="buscar_producto_oculto" wire:model.live='buscar_producto_oculto'>
                        </div>
                        <div class="col-12">
                            <label class="visually-hidden" for="buscar_producto">Buscar Compra</label>
                            <div class="input-group">
                                <div class="input-group-text"><i class="fas fa-search"></i></div>
                                <input type="text" class="form-control" id="buscar_producto"
                                    placeholder="Escanear / Buscar producto por nombre de código" wire:model.live.debounce.500ms='buscar_producto' autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            encargar artículos <span class="text-red">(*)</span>
                        </div>
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead class="table-secondary">
                                   <tr class="text-center">
                                        <th>#</th>
                                        <th>Codigo</th>
                                        <th>nombre del producto</th>
                                        <th>Costo unitario neto</th>
                                        <th>Stock actual</th>
                                        @if ($configuracion->farmacia == 1)
                                        <th>F.Vencimiento</th>
                                        @endif
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Impuesto</th>
                                        <th>Total parcial</th>
                                        <th></th>
                                   </tr>
                                </thead>
                                @if (count($comprasform->detalle_compra) == 0)
                                <tbody>
                                    <tr class="text-center">
                                        <td colspan="11">Datos no disponibles</td>
                                    </tr>
                                </tbody>
                                @else
                                <tbody>
                                        @php $contador = 0; @endphp
                                        @foreach ($comprasform->detalle_compra as $key=> $detcom)
                                        <tr class="text-center">
                                            <td>{{$contador+1}}</td>
                                            <td>{{$key}}</td>
                                            <td>{{$comprasform->detalle_compra[$key]['nombre_producto']}}</td>
                                            <td>{{$comprasform->detalle_compra[$key]['costo_unitario']}}</td>
                                            <td>{{$comprasform->detalle_compra[$key]['stock_actual']}}</td>
                                            @if ($configuracion->farmacia == 1)
                                            @php $valor_fecha = 'comprasform.detalle_compra.'.$key.'.fecha_vencimiento_producto';@endphp
                                            <td><input type="date" id="fecha_vencimiento_producto_{{$key}}" class="form-control"  wire:model.live.debounce.500ms='{{$valor_fecha}}' wire:click="actualizar_item_compra('{{$key}}')"></td>
                                            @endif
                                            @php $valor_cantidad = 'comprasform.detalle_compra.'.$key.'.cantidad';@endphp
                                            <td><input type="number" id="input_cantidad_{{$key}}" class="form-control"  min="1" wire:model.live.debounce.1000ms='{{$valor_cantidad}}' wire:click="actualizar_item_compra('{{$key}}')"></td>
                                            <td>{{$comprasform->detalle_compra[$key]['descuento']}}</td>
                                            <td>{{$comprasform->detalle_compra[$key]['impuesto']}}</td>
                                            <td>{{number_format($comprasform->detalle_compra[$key]['total_parcial'],2)}}</td>
                                            <td>
                                                <button class="btn btn-outline-success" id="editar_item_compra_{{$key}}"  wire:loading.attr="disabled" wire:target="editar_item_compra('{{$key}}')"  wire:click="editar_item_compra('{{$key}}')"><i class="fas fa-edit" ></i></button>
                                                <button class="btn btn-outline-danger" id="eliminar_item_compra_{{$key}}" wire:click="eliminar_item_compra('{{$key}}')"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                                @endif
                            </table>
                        </div>
                        <div>
                            @error('comprasform.detalle_compra')<div class="p-1" style="color:red;"> {{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        @if ($editar_item)
                        <div class="col-md-6 mt-4">
                            <table class="table table-success table-sm">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center"><b>{{strtoupper($comprasform->detalle_compra[$editar_item_id]['nombre_producto'])}}</b></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="item_costo_producto">Costo del producto <span style="color:red;">*</span></label>
                                            <input id="item_costo_producto" wire:model='item_costo_producto' class="form-control" type="number" step="0.01">
                                        </td>
                                        <td>
                                            <label for="item_metodo_impuesto">Metodo de Impuesto<span style="color:red;">*</span></label>
                                            <select class="form-select" id="item_metodo_impuesto" wire:model='item_metodo_impuesto'>
                                                <option value="exclusivo">Exclusivo</option>
                                                <option value="inclusivo">Inclusivo</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="item_impuesto_orden">Impuesto de orden<span style="color:red;">*</span></label>
                                            <input id="item_impuesto_orden"  wire:model='item_impuesto_orden' class="form-control" type="number">
                                        </td>
                                        <td>
                                            <label for="item_metodo_descuento">Metodo de Descuento<span style="color:red;">*</span></label>
                                            <select class="form-select" id="item_metodo_descuento"  wire:model='item_metodo_descuento'>
                                                <option value="fijado">Fijado</option>
                                                <option value="porcentaje">Porcentaje</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="item_descuento">Descuento<span style="color:red;">*</span></label>
                                            <input id="item_descuento"  wire:model='item_descuento' class="form-control" type="number">
                                        </td>
                                        <td>
                                            <label for="item_compra_unidad">Compra de unidad <span style="color:red;">*</span></label>
                                            <select class="form-select" id="item_compra_unidad"  wire:model='item_compra_unidad'>
                                                <option value="unidad">Unidad</option>
                                                <option value="kg">Kg</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="item_precio_producto">Precio del Venta <span style="color:red;">*</span></label>
                                            <input id="item_precio_producto" wire:model='item_precio_producto' class="form-control" type="number" step="0.01">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><Button class="btn btn-primary"  wire:loading.attr="disabled" wire:target="modificar_item"  wire:click='modificar_item'>Modificar Item</Button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="col-md-6 mt-4">
                        </div>
                        @endif
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3 mt-4">
                            <table class="table table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <td class="bold">Total sin Impuesto</td>
                                        <td><span>{{$comprasform->total_sin_impuesto}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="bold">Impuesto de orden</td>
                                        <td><span>{{number_format($comprasform->impuesto_orden_monto,2)}} ({{number_format($comprasform->impuesto_orden,2)}} %)</span></td>
                                    </tr>
                                    <tr>
                                        <td class="bold">Descuento</td>
                                        <td>
                                            @if ($comprasform->descuento)
                                            {{number_format($comprasform->descuento,2)}}
                                            @else
                                                0.00
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bold">Envío</td>
                                        <td>
                                            @if ($comprasform->envio)
                                                {{number_format($comprasform->envio,2)}}
                                            @else
                                                0.00
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="font-weight-bold">Total</span></td>
                                        <td><span class="font-weight-bold">{{number_format($comprasform->total,2)}}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label class="form-label" for="buscar_compras">Impuesto de orden</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="impuesto_orden" placeholder="0" wire:model.live.debounce.500ms='comprasform.impuesto_orden'>
                                <div class="input-group-text"><i class="fas fa-percentage"></i></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label" for="buscar_compras">Descuento</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="0" min="1" id="buscar_compras" wire:model.live.debounce.500ms='comprasform.descuento'>
                                <div class="input-group-text"><i class="fas fa-dollar-sign"></i></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label" for="buscar_compras">Envío</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="0" min="1" id="buscar_compras" wire:model.live.debounce.500ms='comprasform.envio'>
                                <div class="input-group-text"><i class="fas fa-dollar-sign"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-12">
                            <label for="compra_proveedor" class="form-label">Estado <span class='text-red'>(*)</span></label>
                            <select class="form-select" id="compra_proveedor" wire:model.live='comprasform.estado'>
                                <option value="">Elegir</option>
                                <option value="1">Recibido</option>
                                <option value="2">pendiente</option>
                                <option value="3">orden</option>
                            </select>
                            @error('comprasform.estado')<div class="p-1" style="color:red;"> {{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-12">
                            <label for="comprasform_nota" class="form-label">Nota</label>
                            <textarea class="form-control" id="comprasform_nota" rows="3" wire:model.live='comprasform.nota'></textarea>
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-12">
                            <button class="btn btn-primary" wire:loading.attr="disabled" wire:target="save_compra" wire:click='save_compra'>{{ $titlemodal }} Compra</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @script
    <script>
        $wire.on('cerrar_modal_compra', reservacion => {
            ventana = document.getElementById('cerrar_modal_compra_x').click();
        });
    </script>
    @endscript
</div>



