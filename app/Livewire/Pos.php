<?php

namespace App\Livewire;

use App\Livewire\Forms\AlmacenForm;
use App\Livewire\Forms\CajaForm;
use App\Livewire\Forms\ClientesForm;
use App\Livewire\Forms\GastosForm;
use App\Livewire\Forms\MovimientoForm;
use App\Livewire\Forms\PosVentaForm;
use App\Livewire\Forms\ProductoForm;
use App\Models\Almacen;
use App\Models\Caja;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\CompuestoProducto;
use App\Models\Configuracion;
use App\Models\Gasto;
use App\Models\Marca;
use App\Models\PagoDeuda;
use App\Models\Posventa;
use App\Models\PosventaDetalle;
use App\Models\Producto;
use App\Models\ProductoAlmacen;
use App\Models\Tgasto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Pos extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public GastosForm $gastoform;
    public CajaForm $cajaform;
    public ProductoForm $productoform;
    public ClientesForm $clientesForm;
    public PosVentaForm $posventaform;
    public $cajero;
    public $almacen_id;
    public $seleccionar_almacen;
    public $cliente_id;
    public $productoscompuestos;
    public $categoria_id;
    public $marca_id;
    public $items;
    public $impuesto_porcentaje;
    public $impuesto_monto;
    public $monto_pendiente;
    public $descuento;
    public $envio;
    public $total_pagar,$total_previo_pagar;
    public $total_descuentos_items;
    public $cantidad_recibida;
    public $min_cantidad_recibida;
    public $monto_pago;
    public $cambio;
    public $nota_venta;
    public $nota_pago;
    public $titlemodal = 'Añadir';
    public $configuracion;
    public $bclienteoculto = '', $bcliente = '';
    public $posventa_id_eliminar;
    public $gasto_id_eliminar;
    public $buscar_producto;
    public $simpresora='Descargar';
    private AlmacenForm $almacenform;
    private MovimientoForm $movimientoform;

    public function mount()
    {
        $this->cajero = User::find(Auth::user()->id);
        $this->almacen_id = Almacen::first() ? Almacen::first()->id : null;
        if (isset($this->cajero->almacensuser->first()->almacen_id))
        {
            $this->almacen_id = $this->cajero->almacensuser->first()->almacen_id;
        }
        else {$this->almacen_id = Almacen::first() ? Almacen::first()->id : null;}
        $this->configuracion = Configuracion::find(1);
        $this->items = [];
        $this->updatedAlmacenId();
        $this->impuesto_porcentaje = 0;
        $this->cliente_por_defecto();
        $this->cajaform->caja = $this->cajero->cajas->where('fecha_cierre', false)->first();
    }

    public function cambiar_modo_usuario()
    {
        $user = User::find($this->cajero->id);
        if ( $user->modo == 1)
        {
            $user->modo = 2;
            $user->save();
        }
        elseif( $user->modo == 2)
        {
            $user->modo = 1;
            $user->save();
        }
        $this->mount();
    }

    public function descargar_venta_pdf(Posventa $posventa)
    {
        $this->posventaform->reset();
        return $this->posventaform->descargar_pdf($posventa);
    }
    /*caja*/
    public function updatedBuscarProducto()
    {
        $bproducto = Producto::where('codigo', $this->buscar_producto)->first();
        $balmacen = Almacen::find($this->almacen_id);
        if ($bproducto && $balmacen) {
            $this->agregaritem($bproducto);
        }
    }

    public function descargar_reporte_caja()
    {
        $caja = Caja::where('user_id', Auth::user()->id)->whereNull('fecha_cierre')->first();
        return $this->cajaform->descargar_reporte_caja_pdf($caja);
    }
    /*Cliente*/
    public function updatedBcliente()
    {
        $this->dispatch('activar_buscador_cliente');
        $bcliente = Cliente::where('name', $this->bcliente)->first();
        if ($bcliente == false) {
            $this->reset('bclienteoculto');
        }
    }

    public function modal_cliente()
    {
        $this->reset('titlemodal');
        $this->clientesForm->reset();
    }

    public function guardar_cliente()
    {
        $this->clientesForm->store();
        $this->dispatch('cerrar_modal_cliente');
    }

    public function modal_apertura_caja()
    {
        $this->cajaform->reset();
        $this->cajaform->monto_apertura = 0;
    }

    public function crear_caja()
    {
        $this->cajaform->store();
        $this->dispatch('cerrar_modal_caja');
    }

    public function modal(Gasto $gasto = null)
    {
        $this->reset('titlemodal');
        $this->gastoform->reset();
        if ($gasto->id == true) {
            $this->titlemodal = 'Editar';
            $this->gastoform->set($gasto);
        }
    }

    public function cliente_por_defecto()
    {
        $bcliente = Cliente::find(1);
        if ($bcliente) {
            $this->bcliente = $bcliente->name;
            $this->bclienteoculto = $bcliente->id;
        }
    }

    public function updatedAlmacenId()
    {
        $balmacen = Almacen::find($this->almacen_id);
        $this->seleccionar_almacen = $balmacen == true ? $balmacen->id : null;
        $this->reset(['categoria_id', 'marca_id']);
    }

    public function updatedCategoriaId()
    {
        $this->actualizar_montos();
    }

    public function updatedMarcaId()
    {
        $this->actualizar_montos();
    }

    public function updatedImpuestoPorcentaje()
    {
        $this->actualizar_montos();
        if ($this->cajero->modo == 1) {
        $this->dispatch('dirigir_cursor');
        }
    }

    public function updatedDescuento()
    {
        $this->actualizar_montos();
        if ($this->cajero->modo == 1) {
        $this->dispatch('dirigir_cursor');
        }
    }

    public function updatedEnvio()
    {
        $this->actualizar_montos();
        if ($this->cajero->modo == 1) {
        $this->dispatch('dirigir_cursor');
        }
    }

    public function updatedCantidadRecibida()
    {
        $this->actualizar_Cambio();
    }

    public function updatedMontoPago()
    {
        if($this->monto_pago == false){
            $this->monto_pago =0;
        }
        $this->actualizar_Cambio();
    }

    public function actualizar_Cambio()
    {
        $this->cambio = $this->cantidad_recibida - $this->monto_pago;
        $this->monto_pendiente = $this->total_pagar - $this->monto_pago;
    }

    public function actualizar_montos()
    {
        $this->impuesto_porcentaje = empty($this->impuesto_porcentaje) ? 0 : $this->impuesto_porcentaje;
        $this->descuento = empty($this->descuento) ? 0 : $this->descuento;
        $this->envio = empty($this->envio) ? 0 : $this->envio;

        $total_pagar = 0;
        $total_previo_pagar = 0;
        $total_descuentos_items = 0;

        foreach ($this->items as $item) {
            $total_pagar += $item['importe'];
            $total_descuentos_items += $item['descuento'];
            $total_previo_pagar += $item['importe_previo'];
        }
        $total_pagar = $total_pagar - $this->descuento;
        $this->impuesto_monto = ($total_pagar * ($this->impuesto_porcentaje / 100));
        $total_pagar = $total_pagar + $this->impuesto_monto;
        $total_pagar = $total_pagar + $this->envio;

        $this->total_pagar = $total_pagar;
        $this->total_descuentos_items = $total_descuentos_items;
        $this->total_previo_pagar = $total_previo_pagar;
        $this->cantidad_recibida = $total_pagar;
        $this->min_cantidad_recibida = $total_pagar;
        $this->monto_pago = $total_pagar;
        $this->actualizar_Cambio();
    }

    public function verificar_stock_disponible(Producto $producto, $almacen_id)
    {
        $stock_disponible = false;
        $cantidad_stock_disponible = $this->productoform->obtener_stock_producto($producto->id, $almacen_id);

        if ($producto->tipo == 'estandar') {
            $cantidad_existente = isset($this->items[$producto->codigo]['cantidad']) ? $this->items[$producto->codigo]['cantidad'] : 0;
            #verificar si no productos compuesta en en la lista
            $compuesto_producto = CompuestoProducto::where('producto_asignado_id', $producto->id)->get();
            $lista_cantidades2 = 0;
            foreach ($compuesto_producto as $tey => $pcomp) {
                $lcantidad = isset($this->items[$pcomp->producto_principal->codigo]['cantidad']) ? $this->items[$pcomp->producto_principal->codigo]['cantidad'] : 0;
                $lista_cantidades2 = $lista_cantidades2 + $lcantidad;
            }
            $cantidad_stock_disponible = $cantidad_stock_disponible - $lista_cantidades2;
        } elseif ($producto->tipo == 'compuesto') {
            $cantidad_existente = isset($this->items[$producto->codigo]['cantidad']) ? $this->items[$producto->codigo]['cantidad'] : 0;
            $lista_cantidades = [];
            foreach ($producto->pcompuestos as $key => $pcom) {
                $lista_cantidades[] = isset($this->items[$pcom->producto->codigo]['cantidad']) ? $this->items[$pcom->producto->codigo]['cantidad'] : 0;
            }
            $cantidad_stock_disponible = $cantidad_stock_disponible - max($lista_cantidades);
        }

        if ($cantidad_stock_disponible > $cantidad_existente or $producto->ilimitado == 1) {
            $stock_disponible = true;
        }
        return $stock_disponible;
    }

    public function obtener_stock_disponible(Producto $producto, $almacen_id)
    {
        $cantidad_stock_disponible = $this->productoform->obtener_stock_producto($producto->id, $almacen_id);
        if ($producto->tipo == 'estandar') {
            $cantidad_existente = isset($this->items[$producto->codigo]['cantidad']) ? $this->items[$producto->codigo]['cantidad'] : 0;
            #verificar si no productos compuesta en en la lista
            $compuesto_producto = CompuestoProducto::where('producto_asignado_id', $producto->id)->get();
            $lista_cantidades2 = 0;
            foreach ($compuesto_producto as $tey => $pcomp) {
                $lcantidad = isset($this->items[$pcomp->producto_principal->codigo]['cantidad']) ? $this->items[$pcomp->producto_principal->codigo]['cantidad'] : 0;
                $lista_cantidades2 = $lista_cantidades2 + $lcantidad;
            }
            $cantidad_stock_disponible = $cantidad_stock_disponible - $lista_cantidades2;
        } elseif ($producto->tipo == 'compuesto') {
            $cantidad_existente = isset($this->items[$producto->codigo]['cantidad']) ? $this->items[$producto->codigo]['cantidad'] : 0;
            $lista_cantidades = [];
            foreach ($producto->pcompuestos as $key => $pcom) {
                $lista_cantidades[] = isset($this->items[$pcom->producto->codigo]['cantidad']) ? $this->items[$pcom->producto->codigo]['cantidad'] : 0;
            }
            $cantidad_stock_disponible = $cantidad_stock_disponible - max($lista_cantidades);
        }

        return $cantidad_stock_disponible;
    }

    public function agregaritem(Producto $producto)
    {

        $stock_disponible = $this->verificar_stock_disponible($producto, $this->almacen_id);

        if ($stock_disponible) {
            #si hay guardar
            $cantidad = 1;
            if (array_key_exists($producto->codigo, $this->items)) {
                $cantidad += $this->items[$producto->codigo]['cantidad'];
            }
            $importe = $producto->precio * $cantidad;

            $cantidad = empty($cantidad) ? 1 : $cantidad;
            $item = new PosventaDetalle();
            $item->id = $producto->id;
            $item->codigo = $producto->codigo;
            $item->designacion = $producto->designacion;
            $item->precio = $producto->precio;
            $item->cantidad = $cantidad;
            $item->importe_previo =  $item->precio*$item->cantidad;
            $item->descuento = 0;
            $item->compra = $producto->costo;
            $item->costo_compra = $producto->costo*$cantidad;
            $item->importe =  $item->importe_previo-$item->descuento;
            $item->tipo = $producto->tipo;
            $this->items[$item->codigo] = $item->toArray();
            $this->actualizar_montos();
            #si no hay no guardar indicar que no hay stock
        } else {
            // dd('falta stock');
            $this->dispatch('avertencia_stock');
        }
        $this->reset('buscar_producto');
        if ($this->cajero->modo == 1) {
        $this->dispatch('dirigir_cursor');
        }
    }

    public function eliminar_venta(Posventa $posventa_id)
    {
        $this->posventa_id_eliminar = $posventa_id;
        $this->dispatch('advertencia_eliminar_venta');
    }

    public function eliminar_gasto(Gasto $gasto_id)
    {
        $this->gasto_id_eliminar = $gasto_id;
        $this->dispatch('advertencia_eliminar_gasto');
    }

    #[On('eliminar_pos_venta')]
    public function eliminar_pos_venta($password_id)
    {
        $autorizacion = $this->verificarAutorizacion($password_id);
        if ($autorizacion == false) {
            $this->dispatch('mensaje_error_autorización');
        }
        else
        {
            $this->almacenform = new AlmacenForm();
            $this->movimientoform = new MovimientoForm();
            $saldo = $this->almacenform->agregar_descontar_monto_almacen($this->posventa_id_eliminar->almacen_id, $this->posventa_id_eliminar->monto_pago,'-');
            $this->movimientoform->agregar_movimiento($this->posventa_id_eliminar->id,$this->posventa_id_eliminar->almacen_id,$this->posventa_id_eliminar->monto_pago,$saldo,'+','App\Models\Posventa','Eliminar');

            $this->posventa_id_eliminar->estado_posventa = 'eliminado';
            $this->posventa_id_eliminar->save();
            if ($this->posventa_id_eliminar->m_caja->tmovimiento_caja_id == 3) {
                $this->posventa_id_eliminar->m_caja->caja->monto -= $this->posventa_id_eliminar->m_caja->monto;
                $this->posventa_id_eliminar->m_caja->caja->save();
            }
            $this->posventa_id_eliminar->m_caja->delete();
            $this->posventa_id_eliminar->delete();
            $this->almacen_id = $this->posventa_id_eliminar->almacen_id;
            $this->bclienteoculto = $this->posventa_id_eliminar->cliente_id;
            $this->impuesto_porcentaje = $this->posventa_id_eliminar->impuesto_porcentaje;
            $this->impuesto_monto = $this->posventa_id_eliminar->impuesto_monto;
            $this->descuento = $this->posventa_id_eliminar->descuento;
            $this->envio = $this->posventa_id_eliminar->envio;
            $this->total_pagar = $this->posventa_id_eliminar->total_pagar;
            $this->cantidad_recibida = $this->posventa_id_eliminar->cantidad_recibida;
            $this->monto_pago = $this->posventa_id_eliminar->monto_pago;
            $this->cambio = $this->posventa_id_eliminar->cambio;
            $this->nota_venta = $this->posventa_id_eliminar->nota_venta;
            $this->nota_pago = $this->posventa_id_eliminar->nota_pago;
            $this->items = [];
            foreach ($this->posventa_id_eliminar->posventadetalles as $posventadetalle)
            {
                $this->productoform->actualizar_stock_producto($posventadetalle->producto_id, $this->posventa_id_eliminar->almacen_id, '+', $posventadetalle->producto_cantidad);
                $this->items[$posventadetalle->producto_codigo] =
                    [
                    'id' => $posventadetalle->id,
                    'codigo' => $posventadetalle->producto_codigo,
                    'designacion' => $posventadetalle->producto_nombre,
                    'compra' => $posventadetalle->producto_compra,
                    'precio' => $posventadetalle->producto_precio,
                    'importe_previo' => $posventadetalle->producto_importe_previo,
                    'descuento' => $posventadetalle->producto_descuento,
                    'cantidad' => $posventadetalle->producto_cantidad,
                    'costo_compra' => $posventadetalle->producto_costo_compra,
                    'importe' => $posventadetalle->producto_importe,
                    'tipo' => $posventadetalle->producto_tipo];
            }
            $this->dispatch('cerrar_modal_reporte_caja');
        }
    }

    #[On('eliminar_gasto_venta')]
    public function eliminar_gasto_venta($password_id)
    {
        $autorizacion = $this->verificarAutorizacion($password_id);
        if ($autorizacion == false) {
            $this->dispatch('mensaje_error_autorización');
        }

        if ($this->gasto_id_eliminar->m_caja->tmovimiento_caja_id == 2) {
            $this->gasto_id_eliminar->m_caja->caja->monto += $this->gasto_id_eliminar->m_caja->monto;
            $this->gasto_id_eliminar->m_caja->caja->save();
        }

        $this->almacenform = new AlmacenForm();
            $this->movimientoform = new MovimientoForm();
            $saldo = $this->almacenform->agregar_descontar_monto_almacen($this->gasto_id_eliminar->almacen_id, $this->gasto_id_eliminar->monto,'+');
            $this->movimientoform->agregar_movimiento($this->gasto_id_eliminar->id,$this->gasto_id_eliminar->almacen_id,$this->gasto_id_eliminar->monto,$saldo,'+','App\Models\Posventa','Eliminar');


        $this->gasto_id_eliminar->m_caja->delete();
        $this->gasto_id_eliminar->delete();

        $this->gasto_id_eliminar->delete();
        $this->dispatch('cerrar_modal_reporte_caja');
    }

    public function verificarAutorizacion($password)
    {
        $lista_addministradores = User::role('Administrador')->get();
        $autorizar = false;
        foreach ($lista_addministradores as $key => $ladmin) {

            $autorizar = Hash::check($password, $ladmin->password);
            if ($autorizar) {
                break;
            }
        }
        return $autorizar;
    }

    public function updatedItems()
    {
        if ($this->almacen_id == true) {
            foreach ($this->items as $key => $item) {
                #verificar si la cantidad no supere el stock
                $bproducto = Producto::where('codigo', $item['codigo'])->first();
                $cantidad_stock_disponible = 0;
                if ($bproducto) {
                    #stock disponible actual
                    $cantidad_stock_disponible = $this->productoform->obtener_stock_producto($bproducto->id, $this->almacen_id);
                    if ($bproducto->tipo == 'estandar') {
                        #stock disponible con el carrito
                        $productos_compuestos = CompuestoProducto::where('producto_asignado_id', $bproducto->id)->get();
                        foreach ($productos_compuestos as $con => $pro_com) {
                            $cantidad_existente = isset($this->items[$pro_com->producto_principal->codigo]['cantidad']) ? $this->items[$pro_com->producto_principal->codigo]['cantidad'] : 0;
                            $cantidad_stock_disponible = $cantidad_stock_disponible - $cantidad_existente;
                        }
                    } elseif ($bproducto->tipo == 'compuesto') {
                        $cantidad = [];
                        #descontar productos estandar
                        foreach ($bproducto->pcompuestos as $ale => $pcom) {
                            $cantidad[] = isset($this->items[$pcom->producto->codigo]['cantidad']) ? $this->items[$pcom->producto->codigo]['cantidad'] : 0;
                        }
                        $cantidad_stock_disponible = $cantidad_stock_disponible - max($cantidad);
                        #descontar productos compuestos que conforme otros itmes
                        foreach ($this->items as $lam => $sitem) {
                            if ($sitem['codigo'] != $item['codigo']) {
                                $b2producto = Producto::find($sitem['id']);
                                if ($b2producto->tipo == 'compuesto') {
                                    foreach ($b2producto->pcompuestos as $ale => $pcom2) {
                                        $cantidad2[] = isset($this->items[$pcom2->producto->codigo]['cantidad']) ? $this->items[$pcom2->producto->codigo]['cantidad'] : 0;
                                    }
                                    $cantidad_stock_disponible = $cantidad_stock_disponible - max($cantidad2);
                                }
                            }
                        }
                    }
                }

                if($bproducto->ilimitado == 1){

                }
                elseif ($cantidad_stock_disponible < $item['cantidad']) {
                    $this->items[$key]['cantidad'] = $cantidad_stock_disponible;
                }
                $this->items[$key]['cantidad'] = empty($this->items[$key]['cantidad']) ? 1 : $this->items[$key]['cantidad'];
                $this->items[$key]['descuento'] = empty($this->items[$key]['descuento']) ? 0 : $this->items[$key]['descuento'];
                $this->items[$key]['costo_compra'] = $this->items[$key]['compra'] * $this->items[$key]['cantidad'];
                $this->items[$key]['importe_previo'] = $this->items[$key]['precio'] * $this->items[$key]['cantidad'];
                $this->items[$key]['importe'] =  $this->items[$key]['importe_previo'] - $this->items[$key]['descuento'];
            }
            $this->actualizar_montos();
            if ($this->cajero->modo == 1)
            {
            $this->dispatch('dirigir_cursor');
            }
        }
    }

    public function eliminaritem(string $codigo)
    {
        unset($this->items[$codigo]);
        $this->actualizar_montos();
    }

    public function descargar_pdf($posventa)
    {
        $paper_examen = 0;
        $paper_heigth = 460;
        $items_adicional = 18.2;
        if ($posventa->descuento > 0) {
            $items_adicional = $items_adicional + 2;
        }
        if ($posventa->envio > 0) {
            $items_adicional = $items_adicional + 2;
        }
        if ($posventa->impuesto_monto > 0) {
            $items_adicional = $items_adicional + 2;
        }
        $paper_heigth = $paper_examen + $paper_heigth;

        $configuracion = Configuracion::find(1);
        if ($configuracion) {
            $nombre_archivo = 'comprobante-' . date("F j, Y, g:i a") . '.pdf';
            $consultapdf = FacadePdf::loadView('administrador.pdf.comprobante', compact('posventa'))->setPaper([0, 0, $configuracion->ancho_impresion, $paper_heigth + $items_adicional * 2 * $posventa->posventadetalles->count()]);
            $pdfContent = $consultapdf->output();
            return response()->streamDownload(
                fn () => print($pdfContent),
                $nombre_archivo
            );
        }
    }

    public function guardarPosVenta()
    {
        $almacen = Almacen::find($this->almacen_id);
        if ($almacen) {
            $cliente = Cliente::find($this->bclienteoculto);
            $posventa = new Posventa();
            $posventa->almacen_id = $almacen->id;
            $posventa->almacen_name = $almacen->nombre;
            $posventa->cliente_id = $this->bclienteoculto;
            $posventa->cliente_name = $cliente->name;
            $posventa->cliente_nit = $cliente->nit;
            $posventa->impuesto_porcentaje = $this->impuesto_porcentaje;
            $posventa->impuesto_monto = $this->impuesto_monto;
            $posventa->descuento = $this->descuento ?? 0;
            $posventa->envio = $this->envio ?? 0;
            $posventa->descuento_items = $this->total_descuentos_items;
            $posventa->total_pagar_previo = $this->total_previo_pagar;
            $posventa->total_pagar = $this->total_pagar;
            $posventa->cantidad_recibida = $this->cantidad_recibida;
            $posventa->monto_pago = $this->monto_pago;
            $posventa->monto_pendiente = $this->monto_pendiente;
            $posventa->cambio = $this->cambio;
            $posventa->nota_venta = $this->nota_venta ?? '';
            $posventa->nota_pago = $this->nota_pago ?? '';
            $posventa->productos_totales = collect($this->items)->count();
            $posventa->estado_posventa = $this->monto_pendiente > 0 ? "Parcial" : "Completo";
            $posventa->save();
            $this->almacenform = new AlmacenForm();
            $this->movimientoform = new MovimientoForm();
            $saldo = $this->almacenform->agregar_descontar_monto_almacen($posventa->almacen_id, $posventa->monto_pago,'+');
            $this->movimientoform->agregar_movimiento($posventa->id,$posventa->almacen_id,$posventa->monto_pago,$saldo,'+','App\Models\Posventa','crear');


            $posventa->m_caja()->create(['tmovimiento_caja_id' => '3', 'caja_id' => $this->cajaform->caja->id, 'signo' => '+', 'monto' => $this->monto_pago]);
            $this->cajaform->caja->monto += $this->monto_pago;
            $this->cajaform->caja->save();

            $cliente->deuda_total += $this->monto_pendiente;
            $cliente->save();

            foreach ($this->items as $item) {
                // $producto->stock -= $item['cantidad'];
                $posventa_detalle = new PosventaDetalle();
                $posventa_detalle->producto_id = $item['id'];
                $posventa_detalle->producto_codigo = $item['codigo'];
                $posventa_detalle->producto_nombre = $item['designacion'];
                $posventa_detalle->producto_compra = $item['compra'];
                $posventa_detalle->producto_precio = $item['precio'];
                $posventa_detalle->producto_cantidad = $item['cantidad'];
                $posventa_detalle->producto_costo_compra = $item['costo_compra'];
                $posventa_detalle->producto_importe_previo = $item['importe_previo'];
                $posventa_detalle->producto_descuento = $item['descuento'];
                $posventa_detalle->producto_precio = $item['precio'];
                $posventa_detalle->producto_importe = $item['importe'];
                $posventa_detalle->producto_tipo = $item['tipo'];
                $posventa_detalle->posventa_id = $posventa->id;
                $posventa_detalle->save();
                $bcodigo = Producto::where('codigo', $item['codigo'])->first();
                $this->productoform->actualizar_stock_producto($bcodigo->id, $posventa->almacen_id, '-', $posventa_detalle->producto_cantidad);
            }
            #pdf descargar
            $paper_examen = 0;
            $paper_heigth = 460;
            $items_adicional = 18.2;
            if ($posventa->descuento > 0) {
                $items_adicional = $items_adicional + 2;
            }
            if ($posventa->envio > 0) {
                $items_adicional = $items_adicional + 2;
            }
            if ($posventa->impuesto_monto > 0) {
                $items_adicional = $items_adicional + 2;
            }

            $paper_heigth = $paper_examen + $paper_heigth;
            $configuracion = Configuracion::find(1);
            $nombre_archivo = 'comprobante-'.strtotime("now").'.pdf';
            $consultapdf = FacadePdf::loadView('administrador.pdf.comprobante', compact('posventa', 'configuracion'))->setPaper([0, 0, $configuracion->ancho_impresion, $paper_heigth + $items_adicional * 2 * count($this->items)]);
            $this->dispatch('cerrar_modal_postventa');
            $this->reiniciar();
            $pdfContent = $consultapdf->output();
            if (! File::exists(storage_path('app/public/') . 'ticketpdf/')) {
                File::makeDirectory(storage_path('app/public/') . 'ticketpdf/');
            }
            $nombreticketpdf ='ticketpdf/'.$nombre_archivo;
            $consultapdf->save(storage_path('app/public/') . $nombreticketpdf);
            $posventa->pdf = $nombreticketpdf;
            $posventa->save();
            $pdf_enviar = asset('storage/'.$posventa->pdf);
            $datos_impresion = [];
            $datos_impresion[] = $this->simpresora;
            $datos_impresion[] = $pdf_enviar;
            if ($this->simpresora == 'Imprimir')
            {
                $this->dispatch('enviar_to_imprimir',$datos_impresion);
            }
            else {
                return response()->streamDownload(
                    fn () => print($pdfContent),
                    $nombre_archivo
                );
            }
        } else {
            $this->dispatch('advertencia_almacen');
        }
        $this->cliente_por_defecto();
    }

    public function guardar()
    {
        $this->validate([
            'gastoform.monto' => 'required|numeric|between:1,' . ($this->cajaform->caja->monto),
        ]);
        if (isset($this->gastoform->gasto->id)) {
            $this->gastoform->update();
        } else {
            $this->gastoform->store();
            $this->gastoform->gasto->m_caja()->create(['tmovimiento_caja_id' => '2', 'caja_id' => $this->cajaform->caja->id, 'signo' => '-', 'monto' => $this->gastoform->gasto->monto]);
            $this->cajaform->caja->monto -= $this->gastoform->gasto->monto;
            $this->cajaform->caja->save();
            $this->gastoform->reset();
        }
        $this->dispatch('cerrar_modal_gasto');
    }

    public function reiniciar()
    {
        $this->reset([
            'almacen_id',
            'cliente_id',
            'categoria_id',
            'marca_id',
            'items',
            'impuesto_porcentaje',
            'impuesto_monto',
            'descuento',
            'envio',
            'total_pagar',
            'cantidad_recibida',
            'min_cantidad_recibida',
            'monto_pago',
            'cambio',
            'nota_venta',
            'nota_pago',
        ]);
        $this->items = [];
        $this->mount();
    }

    public function cerrar_caja(Caja $caja_id)
    {
        $caja_id->fecha_cierre = now();
        $caja_id->save();
        return $this->cajaform->descargar_reporte_caja_pdf($caja_id);
    }

    public function render()
    {
        $clientes = Cliente::all();
        $almacens = Almacen::all();
        $tgastos = Tgasto::all();

        $productos = ProductoAlmacen::query()
            ->with('producto', 'producto.categoria', 'producto.marca', 'producto.cunitario')
            ->whereExists(function ($query) {
                $query->select()
                    ->from(DB::raw('productos'))
                    ->whereColumn('producto_almacens.producto_id', 'productos.id')
                    ->where('producto_almacens.almacen_id', $this->seleccionar_almacen)
                    ->where('productos.designacion', 'like', '%' . $this->buscar_producto . '%');
            })->where('estado',true);

        $productos->when($this->categoria_id <> '', function ($q) {
            return $q->whereExists(function ($query) {
                $query->select()
                    ->from(DB::raw('productos'))
                    ->whereColumn('producto_almacens.producto_id', 'productos.id')
                    ->where('producto_almacens.almacen_id', $this->seleccionar_almacen)
                    ->where('productos.categoria_id', $this->categoria_id);
            });
        });

        $productos->when($this->marca_id <> '', function ($q) {
            return $q->whereExists(function ($query) {
                $query->select()
                    ->from(DB::raw('productos'))
                    ->whereColumn('producto_almacens.producto_id', 'productos.id')
                    ->where('producto_almacens.almacen_id', $this->seleccionar_almacen)
                    ->where('productos.marca_id', $this->marca_id);
            });
        });


        $productos =  $productos->paginate(10);
        $categorias = $productos ? $productos->pluck('producto.categoria')->unique() : Categoria::all();
        $marcas =  $productos ? $productos->pluck('producto.marca')->unique() : Marca::all();

        return view('livewire.pos', compact('clientes', 'almacens', 'tgastos', 'productos', 'categorias', 'marcas'))->layout('administrador.ventas.pos');
    }
}
