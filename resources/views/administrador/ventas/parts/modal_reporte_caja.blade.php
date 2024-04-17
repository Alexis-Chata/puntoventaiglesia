<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#modalReporteCajaLabel">
    Reporte Caja
</button>

<!-- Modal -->
<div class="modal fade " data-bs-focus="false" id="modalReporteCaja" tabindex="-1" aria-labelledby="modalReporteCajaLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalReporteCajaLabel">Reporte de Caja</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="cerrar_modal_reporte_caja_x"></button>
            </div>
            <div class="modal-body">
                @if ($cajero->cajas->where('fecha_cierre', false)->count() > 0)
                    <div class="row mb-3">
                        <div class="col-12 my-1">
                            <b>Nombre Cajero :</b> {{ $cajero->name . ' ' . $cajero->lastname }}<br>
                            <b> Caja Aperturada : </b>
                            {{ $cajero->cajas->where('fecha_cierre', false)->first()->fecha_apertura }} <br>
                            @if ($cajero->cajas->where('fecha_cierre', false)->first()->mcajas->first())
                                <b>Monto Inicial :</b>
                                {{ $configuracion->moneda->simbolo }}.{{ $cajero->cajas->where('fecha_cierre', false)->first()->mcajas->first()->monto }}
                                <br>
                                <b>Monto Actual :</b>
                                {{ $configuracion->moneda->simbolo }}.{{ $cajero->cajas->where('fecha_cierre', false)->first()->monto }}
                                <br>
                            @else
                                <b>Monto Inicial :</b> {{ $configuracion->moneda->simbolo }}.0
                            @endif
                        </div>
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Tipo de movimiento</th>
                                        <th class="text-center">Cliente</th>
                                        <th class="text-center">Signo</th>
                                        <th class="text-center">Ingreso</th>
                                        <th class="text-center">Egreso</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cajero->cajas->where('fecha_cierre', false)->first()->mcajas as $mcaja)
                                        <tr>
                                            <td class="text-center">{{ $mcaja->tmovmientocaja->name }}</td>
                                            <td class="text-center">@if ($mcaja->tmovimiento_caja_id == 3) {{$mcaja->m_cajable->cliente_name}} @else - @endif</td>
                                            <td class="text-center">{{ $mcaja->signo }}</td>
                                            <td class="text-center">
                                                @if ($mcaja->signo == '+')
                                                    {{ $configuracion->moneda->simbolo . $mcaja->monto }}
                                                @else
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($mcaja->signo == '-')
                                                    {{ $configuracion->moneda->simbolo . $mcaja->monto }}
                                                @else
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($mcaja->tmovimiento_caja_id == 3)
                                                    <button class="btn btn-success" wire:loading.attr='disabled' id="venta_descargar-{{$mcaja->m_cajable_id}}" wire:target='descargar_venta_pdf({{$mcaja->m_cajable_id}})' wire:click='descargar_venta_pdf({{$mcaja->m_cajable_id}})'><i class="bi bi-download"></i></button>
                                                    <input wire:click="eliminar_venta('{{ $mcaja->m_cajable_id }}')"
                                                        wire:loading.attr="disabled" class="btn btn-danger" type="button"
                                                        id="Eliminar_venta-{{$mcaja->id}}" value="Eliminar venta">
                                                @elseif($mcaja->tmovimiento_caja_id == 2)
                                                <input wire:click="eliminar_gasto('{{ $mcaja->m_cajable_id }}')"
                                                wire:loading.attr="disabled" class="btn btn-danger" type="button"
                                                id="Eliminar_gasto-{{$mcaja->id}}" value="Eliminar gasto">
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="table-dark text-center">Total</td>
                                        <td class="table-success text-center">
                                            {{ $configuracion->moneda->simbolo . $cajero->cajas->where('fecha_cierre', false)->first()->mcajas->where('signo', '+')->sum('monto') }}
                                        </td>
                                        <td class="table-danger text-center">
                                            {{ $configuracion->moneda->simbolo . $cajero->cajas->where('fecha_cierre', false)->first()->mcajas->where('signo', '-')->sum('monto') }}
                                        </td>
                                        <td class="table-info text-center">
                                            {{ $configuracion->moneda->simbolo . ($cajero->cajas->where('fecha_cierre', false)->first()->mcajas->where('signo', '+')->sum('monto') - $cajero->cajas->where('fecha_cierre', false)->first()->mcajas->where('signo', '-')->sum('monto')) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" wire:loading.attr="disabled"
                    wire:target="descargar_reporte_caja" wire:click='descargar_reporte_caja'>Descargar Reporte</button>
            </div>
        </div>
    </div>
    @script
    <script>
        $wire.on('cerrar_modal_reporte_caja', reservacion => {
            ventana = document.getElementById('cerrar_modal_reporte_caja_x').click();
        });
    </script>
    @endscript
</div>
