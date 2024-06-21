<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <!--titulo-->
        <div class="row">
            <div class="col-12 fs-6">
                <span class="fs-3 fw-bold">Lista de Devolución</span> Ventas | Lista de Devolución
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
    <!--cuerpo-->
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><b>Listado de Devoluciones</b></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 col-sm-3">
                                <label  for="buscar_devolucion">Buscar Devolucion</label>
                                <div class="input-group">
                                    <div class="input-group-text"><i class="fas fa-search"></i></div>
                                    <input type="text" class="form-control" id="buscar_devolucion"
                                        placeholder="Buscar Devolución" wire:model.live='search'>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="seleccionar_almacen" class="form-label">Almacen</label>
                                <select class="form-select" id="seleccionar_almacen" wire:model.live="salmacen">
                                    <option value="">Elegir</option>
                                    @foreach ($almacens as $almacen)
                                    <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="f_inicio">F.Inicio</label>
                                <input type="date" id="f_inicio" class="form-control" wire:model.live='finicio'>
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="f_inicio">F.Final</label>
                                <input type="date" id="f_final" class="form-control" wire:model.live='ffinal'>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <button class="btn btn-outline-success" wire:loading.attr="disabled" wire:target="descargar_reporte_devolucions_excel" wire:click="descargar_reporte_devolucions_excel"><i class="fas fa-download"></i> Descargar Reporte</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr class="text-center">
                                            <th>Fecha</th>
                                            <th>Almacen</th>
                                            <th>Cliente</th>
                                            <th>Impuesto Porcentaje</th>
                                            <th>Impuesto</th>
                                            <th>Descuento</th>
                                            <th>Envio</th>
                                            <th>Total a Pagar</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($devolucions as $devolucion)
                                        <tr role="row" class="accordion-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#tablecollapse-{{$devolucion->id}}" aria-expanded="false" aria-controls="tablecollapse-{{$devolucion->id}}">
                                            <td role="cell" class="text-center"  style="vertical-align: middle;">{{ $devolucion->created_at }}</td>
                                            <td role="cell" class="text-center" style="vertical-align: middle;">{{ $devolucion->almacen_name }}</td>
                                            <td role="cell" class="text-center" style="vertical-align: middle;">{{ $devolucion->cliente_name }}</td>
                                            <td role="cell" class="text-center" style="vertical-align: middle;">{{ $devolucion->impuesto_porcentaje }}%</td>
                                            <td role="cell" class="text-center" style="vertical-align: middle;">{{ $configuracion->moneda->simbolo.$devolucion->impuesto_monto }}</td>
                                            <td role="cell" class="text-center" style="vertical-align: middle;">{{ $configuracion->moneda->simbolo.$devolucion->descuento }}</td>
                                            <td role="cell" class="text-center" style="vertical-align: middle;">{{ $configuracion->moneda->simbolo.$devolucion->envio }}</td>
                                            <td role="cell" class="text-center" style="vertical-align: middle;">{{ $configuracion->moneda->simbolo.$devolucion->total_pagar }}</td>
                                            <td role="cell" class="text-center" style="vertical-align: middle;">
                                                <button class="btn btn-danger" wire:loading.attr='disabled' id="venta_descargar-{{$devolucion->id}}" wire:target='descargar_devolucion_pdf({{$devolucion->id}})' wire:click='descargar_devolucion_pdf({{$devolucion->id}})'><i class="fas fa-download"></i></button>
                                            </td>
                                        </tr>
                                        <tr role="row">
                                            <td role="cell" colspan="9" class="p-0">
                                                <div id="tablecollapse-{{$devolucion->id}}" class="accordion-collapse collapse" aria-labelledby="heading-1" data-bs-parent="#accordionExample" style="">
                                                    <div>
                                                        <table role="table" width="100%" class="table table-primary">
                                                            <thead role="rowgroup">
                                                                <tr>
                                                                    <th colspan="7"><center>DETALLE DE LA DEVOLUCIÓN</center></th>
                                                                </tr>
                                                                <tr role="row">
                                                                    <th class="text-center" role="columnheader">Codigo</th>
                                                                    <th class="text-center" role="columnheader">Producto</th>
                                                                    <th class="text-center" role="columnheader">Cantidad</th>
                                                                    <th class="text-center" role="columnheader">Precio</th>
                                                                    <th class="text-center" role="columnheader">importe</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody role="rowgroup">
                                                                @foreach ($devolucion->devoluciondetalles as $ddetalle)
                                                                <tr role="row">
                                                                    <td role="cell" class="text-center"> {{$ddetalle->producto_codigo}} </td>
                                                                    <td role="cell" class="text-center"> {{$ddetalle->producto_nombre}} </td>
                                                                    <td role="cell" class="text-center"> {{$ddetalle->producto_cantidad}} </td>
                                                                    <td role="cell" class="text-center"> {{$configuracion->moneda->simbolo.$ddetalle->producto_precio}} </td>
                                                                    <td role="cell" class="text-center"> {{$configuracion->moneda->simbolo.$ddetalle->producto_importe}}  </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        @endforelse
                                            <tr>
                                                <td colspan="4" class="table-dark text-center">
                                                    Total
                                                </td>
                                                <td class="text-center table-success">{{$configuracion->moneda->simbolo.$devolucions->sum('impuesto_monto')}}</td>
                                                <td class="text-center table-success">{{$configuracion->moneda->simbolo.$devolucions->sum('descuento')}}</td>
                                                <td class="text-center table-success">{{$configuracion->moneda->simbolo.$devolucions->sum('envio')}}</td>
                                                <td class="text-center table-success">{{$configuracion->moneda->simbolo.$devolucions->sum('total_pagar')}}</td>
                                                <td class="text-center table-dark"></td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-9">
                                {{ $devolucions->links() }}
                            </div>
                            <div class="col-12 col-sm-3" style="text-align: right;" wire:model.live='pagina'>
                                <select class="form-select">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="100">100</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
