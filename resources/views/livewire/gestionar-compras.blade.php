<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <!--titulo-->
        <div class="row">
            <div class="col-12 fs-6">
                <span class="fs-3 fw-bold">Lista de Compras</span> Compras | Lista de Compras
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
                            <span><b>Listado de Compras</b></span>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalCompra" wire:click='modal'><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 col-sm-3">
                                <label  for="buscar_compras_c">Buscar Compra</label>
                                <div class="input-group">
                                    <div class="input-group-text"><i class="fas fa-search"></i></div>
                                    <input type="text" class="form-control" id="buscar_compras_c"
                                        placeholder="Buscar Compra" wire:model.live='search'>
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
                                <button class="btn btn-outline-success" wire:loading.attr="disabled" wire:target='descargar_reporte_compras_pdf' wire:click='descargar_reporte_compras_pdf'><i class="fas fa-file"></i> PDF</button>
                                <button class="btn btn-outline-danger" wire:loading.attr="disabled" wire:target="descargar_reporte_compras_excel" wire:click="descargar_reporte_compras_excel"><i class="fas fa-download"></i> EXCEL</button>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr class="text-center">
                                            <th>Fecha</th>
                                            <th>Referencia</th>
                                            <th>Proveedor</th>
                                            <th>Almacen</th>
                                            <th>Estado</th>
                                            <th>Total</th>
                                            <th>Pagado</th>
                                            <th>Debido</th>
                                            <th>Estado de Pago</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($compras as $compra)
                                            <tr class="text-center">
                                                <td style="vertical-align: middle;">{{ $compra->fecha }}</td>
                                                <td style="vertical-align: middle;">{{ "COM_".$compra->id }}</td>
                                                <td style="vertical-align: middle;">{{ $compra->proveedor->name }}</td>
                                                <td style="vertical-align: middle;">{{ $compra->almacen->nombre }}</td>
                                                <td style="vertical-align: middle;">
                                                    @if ($compra->estado == 1)
                                                    <span class="badge text-bg-success">Recibido</span>
                                                    @elseif ($compra->estado == 2)
                                                    <span class="badge text-bg-primary">Pendiente</span>
                                                    @elseif ($compra->estado == 3)
                                                    <span class="badge text-bg-warning">Ordenado</span>
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle;">{{ $configuracion->moneda->simbolo.$compra->total }}</td>
                                                <td style="vertical-align: middle;">{{ $configuracion->moneda->simbolo.$compra->pagado }}</td>
                                                <td style="vertical-align: middle;">{{ $configuracion->moneda->simbolo.$compra->debido }}</td>
                                                <td style="vertical-align: middle;">
                                                    @if ($compra->estado_pago == 1)
                                                        <button class="btn btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalpagocompra"
                                                        id="agregar-pago-compra-{{ $compra->id }}"
                                                         wire:click="modal_pago_compra({{$compra->id}})">
                                                        No Pagado
                                                        </button>
                                                    @elseif($compra->estado_pago == 2)
                                                    <button class="btn btn-success" disabled>Pagado</button>
                                                    @elseif($compra->estado_pago == 3)
                                                        <button class="btn btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalpagocompra"
                                                        id="agregar-pago-compra-{{ $compra->id }}"

                                                         >Parcial</button>
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <div class="dropdown">
                                                        <button class="btn" type="button" id="dropdownMenuAcciones-1" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuAcciones-1">
                                                        <li>
                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalCompra" id="editar-compra-{{ $compra->id }}" wire:click="modal('{{ $compra->id }}')" href="#"><i class="fas fa-edit"></i> Editar Compra</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalreportepagoscompra" id="consultar-pago-compra-{{ $compra->id }}" wire:click="modal_pago_compra({{$compra->id}})" href="#"><i class="fas fa-pager"></i> Ver Pagos</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" wire:click="eliminar({{ $compra->id }})"
                                                                id="eliminar-compra-{{ $compra->id }}"
                                                                wire:confirm="Estas seguro de Eliminar esta Compra?" href="#"><i class="fas fa-trash"></i>Eliminar Compra</a>
                                                        </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-9">
                                {{ $compras->links() }}
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
                    @include('administrador.compras.parts.compra-modal')
                    @include('administrador.compras.parts.registrar-pago-compra-modal')
                    @include('administrador.compras.parts.reporte-pagos-compra-modal')
                </div>
            </div>
        </div>

</div>

