<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#modalProducto">
    Modal Producto
</button>

<!-- Modal -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalProductoLabel">{{ $titlemodal }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="cerrar_modal_producto_x"></button>
            </div>
            <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <label for="designacion" class="form-label">Nombre del Producto <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designacion"
                                placeholder="Escribe el producto" wire:model="productoForm.designacion">
                            @error('productoForm.designacion')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <label for="simbologia">Simbología de códigos de barras<span  class="text-danger">*</span></label>
                            <select id="simbologia"  class="form-select" wire:model="productoForm.simbologia">
                                <option value="">Elegir</option>
                                <option value="C128">Code 128</option>
                                <option value="C39">Code 39</option>
                                <option value="EAN 8">EAN8</option>
                                <option value="EAN 13">EAN13</option>
                                <option value="UPC-A">UPC</option>
                            </select>
                            @error('productoForm.simbologia')
                            <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <label for="codigo" class="form-label">Codigo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="codigo" placeholder="Escribir Codigo"wire:model="productoForm.codigo">
                                <div class="input-group-text"  wire:click='generar_codigo'><i class="fas fa-barcode"></i></div>
                            </div>
                            @error('productoForm.codigo')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4">
                                <label for="buscar_marca_oculto" class="form-label">Marca <span class='text-red'>(*)</span></label>
                                <input type="hidden" id="buscar_marca_oculto" wire:model.live='buscar_marca_oculto'>
                                <input type="text" class="form-control" id="buscar_marca" wire:model.live='buscar_marca' placeholder="Buscar Marca"
                                 wire:model.live.debounce.500ms='buscar_marca' autocomplete="off">
                            @error ('productoForm.marca_id')<span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label" for="impuesto_orden">Impuesto de orden</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="impuesto_orden" placeholder="0" wire:model.live.debounce.500ms="productoForm.impuesto_orden">
                                <div class="input-group-text"><i class="fas fa-percentage"></i></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                                <label for="metodo_impuesto">Metodo de Impuesto<span style="color:red;">*</span></label>
                                <select class="form-select" id="metodo_impuesto" wire:model="productoForm.metodo_impuesto">
                                    <option value="">Elegir</option>
                                    <option value="exclusivo">Exclusivo</option>
                                    <option value="inclusivo">Inclusivo</option>
                                </select>
                                @error('productoForm.metodo_impuesto')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="buscar_categoria_oculto" class="form-label">Categoria <span class='text-red'>(*)</span></label>
                            <input type="hidden" id="buscar_categoria_oculto" wire:model.live='buscar_categoria_oculto'>
                            <input type="text" class="form-control" id="buscar_categoria" wire:model.live='buscar_categoria' placeholder="Buscar Categoría"
                             wire:model.live.debounce.500ms='buscar_categoria' autocomplete="off">
                            @error('productoForm.categoria_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" rows="3"  wire:model="productoForm.descripcion"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4">
                            <label for="metodo_impuesto">Tipo<span style="color:red;">*</span></label>
                            <select class="form-select" id="metodo_impuesto" wire:model.live="productoForm.tipo">
                                <option value="">Elegir</option>
                                <option value="estandar">Producto Estandar</option>
                                <option value="compuesto">Producto Compuesto</option>
                            </select>
                            @error('productoForm.tipo')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <label for="costo" class="form-label">Costo del Producto <span style="color:red;">*</span></label>
                            <input type="number" class="form-control" step="0.01" id="costo"
                                wire:model="productoForm.costo">
                            @error('productoForm.costo')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <label for="precio" class="form-label">Precio del Producto <span style="color:red;">*</span></label>
                            <input type="number" class="form-control" step="0.01" id="precio"
                                wire:model="productoForm.precio">
                            @error('productoForm.precio')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="producto_unitario">Producto Unitario<span style="color:red;">*</span></label>
                            <select class="form-select" id="producto_unitario" wire:model="productoForm.unitario">
                                <option value="">Elegir</option>
                                @foreach ($unidades as $uni)
                                    <option value="{{$uni->id}}">{{$uni->name}}</option>
                                @endforeach
                            </select>
                            @error('productoForm.unitario')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="producto_venta_unidad">Venta de Unidades<span style="color:red;">*</span></label>
                            <select class="form-select" id="producto_venta_unidad" wire:model="productoForm.venta_unidad">
                                <option value="">Elegir</option>
                                @foreach ($unidades as $uni)
                                    <option value="{{$uni->id}}">{{$uni->name}}</option>
                                @endforeach
                            </select>
                            @error('productoForm.venta_unidad')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="producto_venta_unidad">Compra de Unidad<span style="color:red;">*</span></label>
                            <select class="form-select" id="producto_compra_unidad" wire:model="productoForm.compra_unidad">
                                <option value="">Elegir</option>
                                @foreach ($unidades as $uni)
                                <option value="{{$uni->id}}">{{$uni->name}}</option>
                                @endforeach
                            </select>
                            @error('productoForm.venta_unidad')
                            <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="alerta_stock" class="form-label">Alerta stock </label>
                            <input type="number" class="form-control" id="alerta_stock"
                                wire:model="productoForm.alerta_stock">
                            @error('productoForm.alerta_stock')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="alerta_stock" class="form-label">Ilimitado </label>
                            <input type="checkbox" id="producto_ilimitado"
                                wire:model.live="productoForm.ilimitado"> {{$productoForm->ilimitado}}
                        </div>
                    </div>
                    @if ($productoForm->tipo == 'compuesto')
                    <div class="row my-2">
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4">
                            <input type="text" class="form-control" id="buscar_producto2" wire:model.live='bproducto' placeholder="Escriba el nombre del producto agregar o el codigo">
                        </div>
                        <div class="col-12 col-sm-4">
                            <button class="btn btn-success" wire:loading.attr="disabled" wire:target="agregar_producto_compuesto" wire:click='agregar_producto_compuesto'>
                                Agregar Producto
                            </button>
                        </div>
                        <div class="col-12">
                            @error('productoForm.productos_compuesto')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr class="text-center">
                                        <th style="width:180px;">Codigo Producto</th>
                                        <th>Producto</th>
                                        <th style="width:120px;">Precio</th>
                                        <th style="width:120px;">Cantidad</th>
                                        <th class="text-center">Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="table-secondary">
                                    @foreach ($productoForm->productos_compuesto as $key => $item)
                                    <tr>
                                        @php
                                            $nombre_codigo = "productoForm.productos_compuesto."."$key".".codigo";
                                            $nombre_nombre = "productoForm.productos_compuesto."."$key".".nombre";
                                            $nombre_precio = "productoForm.productos_compuesto."."$key".".precio";
                                            $nombre_cantidad = "productoForm.productos_compuesto."."$key".".cantidad";
                                        @endphp
                                        <td><input class="form-control text-center" id="producto_compuesto_codigo_{{$key}}" disabled type="text" wire:model.live='{{$nombre_codigo}}'></td>
                                        <td><input class="form-control" id="producto_compuesto_nombre_{{$key}}" disabled type="text" wire:model.live='{{$nombre_nombre}}'></td>
                                        <td class="text-center">{{$configuracion->moneda->simbolo}}{{$productoForm->productos_compuesto[$key]['precio']}}</td>
                                        <td><input class="form-control text-center" id="producto_compuesto_cantidad_{{$key}}" type="number" step="0.01" wire:model.live.debounce.500ms='{{$nombre_cantidad}}'></td>
                                        <td class="text-center">{{$configuracion->moneda->simbolo}}{{$productoForm->productos_compuesto[$key]['total']}}</td>
                                        <td class="text-center">
                                            <button class="btn btn-danger" wire:loading.attr="disabled" id="eliminar_producto_compuesto_{{$key}}" wire:target="eliminar_producto_compuesto('{{$key}}')" wire:click="eliminar_producto_compuesto('{{$key}}')"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="text-center table-dark">Total</td>
                                        <td  class="text-center">
                                            {{$productoForm->productos_compuesto_total}}
                                        </td>
                                        <td class="table-dark"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen-{{ $iteration }}"
                                placeholder="Ingrese Imagen" wire:model.live="imagen_producto">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" wire:loading.attr="disabled" wire:target="guardar" wire:click='guardar'
                    class="btn btn-primary">{{ $titlemodal }}</button>
            </div>
        </div>
    </div>
</div>
