<div>

    <div class="row align-items-end">
        <div class="col-sm-3 col-12 mb-3">
            <label for="">Elegir Almacen</label>
            <select name="almacen" id="" class="form-select" wire:model='salmacen'>
                    <option value="">Elegir</option>
                    @foreach ($almacens as $almacen)
                    <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                    @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-3 mb-3" >
            <label for="fecha_inicial">Fecha Inicial</label>
            <input type="date" class="form-control" wire:model.live='fecha_inicial' id="fecha_inicial">
        </div>
        <div class="col-12 col-sm-3 mb-3" >
            <label for="fecha_final">Fecha Final</label>
            <input type="date" class="form-control" wire:model.live='fecha_final'  id="fecha_final">
        </div>
        <div class="col-12 col-sm-3 mb-3">
            <button class="btn btn-success" wire:loading.attr="disabled" wire:target="descargar_reporte_general_excel" wire:click='descargar_reporte_general_excel'>Descargar Reporte EXCEL</button>
        </div>
        <div class="col-12 col-sm-3 mb-3">
            <button class="btn btn-danger" wire:loading.attr="disabled" wire:target="descargar_reporte_general_pdf" wire:click='descargar_reporte_general_pdf'>Descargar Reporte PDF</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12 fs-3">
            <b>Reporte Ganacias<hr></b>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-2 mb-1">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-xl-4" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4 rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-shopping-cart"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Total de Ventas</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_ventas}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-list"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Total de Costos</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_com_by_vent}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-list"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Ganacia Neta</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_ventas-$monto_com_by_vent}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 fs-3">
            <b>Reporte General<hr></b>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-2 mb-1">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-xl-6" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4 rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-shopping-cart"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Ventas</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_ventas}}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-6" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-list"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Total Ingresos</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_ventas}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-1 mb-1">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-xl-3" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-plus-circle"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Compras</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_compras}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-minus-circle"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Gastos</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_gastos}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-minus-circle"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Devoluciones</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_devoluciones}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-list"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Total Egresos</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_gastos+$monto_devoluciones+$monto_compras}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-1 mb-2">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-xl-4" role="button">
                    <div class="d-flex justify-content-center align-items-center p-4 bg-danger  rounded-3" style="box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-user"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Deuda Por Cobrar</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_deuda}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-list"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Ganacias</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_ventas-$monto_compras-$monto_gastos-$monto_devoluciones}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4" role="button" data-bs-toggle="modal">
                    <div class="d-flex justify-content-center align-items-center p-4  rounded-3" style="background-color: white;box-shadow: 2.5px 2.5px 2.5px rgba(0, 0, 0, 0.3);">
                        <span class="display-6 lh-1 text-blue mb-0"><i style="color:black;" class="fas fa-list"></i></span>
                        <div class="ms-4 h6 fw-normal mb-0">
                            <div class="d-flex">
                                <h5 class="purecounter mb-0 fw-bold">Ganacias con Deuda</h5>
                            </div>
                            <p class="mb-0">{{$configuracion->moneda->simbolo}} {{$monto_ventas-$monto_compras+$monto_deuda-$monto_gastos-$monto_devoluciones}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
