<div>
    <!--titulo-->
    <div>
        <div class="row">
            <div class="col-12 fs-6">
                <span class="fs-3">Ajuste del Sistema</span> Configuraciones | Ajuste del Sistema
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
    </div>
    <!--cuerpo-->
    <div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Ajustes del sistema
                    </div>
                    <div class="card-body">
                        @if (isset($ajustesistemaform->configuracion->logo))
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <center><img src="{{asset($ajustesistemaform->configuracion->logo)}}" width="128px" alt=""></center>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="moneda_id" class="form-label">Moneda Predeterminada</label>
                                <select class="form-select" id="moneda_id" wire:model='ajustesistemaform.moneda_id'>
                                    <option value="">Elegir</option>
                                    @foreach ($monedas as $moneda)
                                    <option value="{{$moneda->id}}">{{$moneda->nombre_moneda}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="email_predeterminado" class="form-label">Email Predeterminado <span style="color:red;">*</span></label>
                                <input type="email" class="form-control" id="email_predeterminado" placeholder="Email Predeterminado" wire:model='ajustesistemaform.email_predeterminado'>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="logo" class="form-label">Cambiar logo</label>
                                <input class="form-control" type="file" id="logo" wire:model='imagen_logo'>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="nombre_empresa" class="form-label">Nombre de Empresa <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="nombre_empresa" wire:model='ajustesistemaform.name' placeholder="Nombre de empresa">
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="telefono_empresa" class="form-label">Telefono de la Empresa <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="telefono_empresa" wire:model='ajustesistemaform.telefono_empresa' placeholder="Telefono de la Empresa">
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="desarrollador" class="form-label">Desarrollado por <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="desarrollador" wire:model='ajustesistemaform.desarrollador' placeholder="Desarrollado por">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="pie_de_pagina" class="form-label">Pie de página <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="pie_de_pagina" wire:model='ajustesistemaform.pie_pagina'  placeholder="Pie de Pagina">
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="almacen_predeterminada" class="form-label">Almacen Predeterminada</label>
                                <select class="form-select" id="almacen_predeterminada" wire:model='ajustesistemaform.almacen_id'>
                                    <option value="">Elegir</option>
                                    @foreach ($almacens as $alma)
                                    <option value="{{$alma->id}}">{{$alma->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="tamano_ticket" class="form-label">Tamaño del Ticket <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="tamano_ticket" wire:model='ajustesistemaform.ancho_impresion' placeholder="Tamaño de Ticket">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="direccion" class="form-label">Dirección <span style="color:red;">*</span></label>
                                <textarea rows="2" class="form-control" id="direccion" wire:model='ajustesistemaform.direccion'></textarea>
                            </div>
                            <div class="col-sm-12">
                                <label for="direccion" class="form-label">Descripción A reporte Auxiliar<span style="color:red;">*</span></label>
                                <textarea rows="2" class="form-control" id="descripcion" wire:model='ajustesistemaform.descripcion'></textarea>
                            </div>
                            <div class="col-sm-12">
                                <label for="direccion" class="form-label">Descripción B reporte Auxiliar <span style="color:red;">*</span></label>
                                <textarea rows="2" class="form-control" id="descripcion2" wire:model='ajustesistemaform.descripcion2'></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <input type="checkbox" id="pagina_factura" @if ($ajustesistemaform->pagina_factura == 1) checked
                                @endif wire:model='ajustesistemaform.pagina_factura'>
                                <label class="form-check-label" for="pagina_factura">Pie de página de la Factura</label>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="pie_pagina_factura" class="form-label">Pie de página de la Factura <span style="color:red;">*</span></label>
                                <textarea rows="2" class="form-control" id="pie_pagina_factura"
                                    wire:model='ajustesistemaform.pie_pagina_factura'></textarea>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <input type="checkbox" @if ($ajustesistemaform->cotizacion_stock == 1) checked
                                @endif id="cotizacion_stock" wire:model='ajustesistemaform.cotizacion_stock'>
                                <label class="form-check-label" for="cotizacion_stock">Crear Cotización con stock</label><br>
                                <input type="checkbox" id="farmacia_checkbox" @if ($ajustesistemaform->farmacia == 1)
                                 checked
                                @endif wire:model='ajustesistemaform.farmacia'>
                                <label class="form-check-label" for="farmacia_checkbox">Farmacia</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <button class="btn btn-primary" wire:loading.attr="disabled" wire:target="save,imagen_logo" wire:click='save'><i class="fas fa-check-circle"></i> Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
