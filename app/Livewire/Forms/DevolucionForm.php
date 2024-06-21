<?php

namespace App\Livewire\Forms;

use App\Models\Configuracion;
use App\Models\Devolucion;
use App\Models\DevolucionDetalle;
use App\Models\Posventa;
use App\Models\PosventaDetalle;
use App\Models\ProductoAlmacen;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class DevolucionForm extends Form
{
    public ?Devolucion $devolucion;
    public ?Posventa $posventa;
    public ComprasForm $comprasform;
    public $fecha;
    public $posventa_id;
    public $almacen_id;
    public $almacen_name;
    public $cliente_id;
    public $cliente_name;
    public $impuesto_porcentaje;
    public $impuesto_monto;
    public $descuento;
    public $total_sin_impuesto;
    public $envio;
    public $total_pagar;
    public $cantidad_recibida;
    public $monto_pago;
    public $cambio;
    public $nota_devolucion;
    public $productos_totales;
    public $estado_devolucion;
    public $detalles_devolucion = [];
    private AlmacenForm $almacenform;
    private MovimientoForm $movimientoform;

    public $rules = [
        'fecha' => 'required',
    ];

    public function agregar_datos_venta(Posventa $posventa)
    {
        $this->fecha = date('Y-m-d');
        $this->posventa = $posventa;
        $this->posventa_id = $posventa->id;
        $this->almacen_id = $posventa->almacen_id;
        $this->almacen_name = $posventa->almacen_name;
        $this->cliente_id = $posventa->cliente_id;
        $this->cliente_name = $posventa->cliente_name;
        $this->impuesto_porcentaje = 0;
        $this->impuesto_monto = 0;
        $this->descuento = $posventa->descuento;
        $this->envio = 0;
        $this->total_pagar = 0;
        $this->cantidad_recibida =0;
        $this->monto_pago = 0;
        $this->cambio = 0;
        $this->nota_devolucion = '';
        $this->productos_totales = 0;
        $this->estado_devolucion = null;

        foreach ($posventa->posventadetalles as $key => $posdetalle)
        {
            $this->detalles_devolucion[$posdetalle->producto_codigo]['id'] = '';
            $this->detalles_devolucion[$posdetalle->producto_codigo]['devolucion_id'] = '';
            $this->detalles_devolucion[$posdetalle->producto_codigo]['detalle_id'] = $posdetalle->id;
            $this->detalles_devolucion[$posdetalle->producto_codigo]['producto_id'] = $posdetalle->producto_id;
            $this->detalles_devolucion[$posdetalle->producto_codigo]['producto_codigo'] = $posdetalle->producto_codigo;
            $this->detalles_devolucion[$posdetalle->producto_codigo]['producto_nombre'] = $posdetalle->producto_nombre;
            $this->detalles_devolucion[$posdetalle->producto_codigo]['producto_precio'] = $posdetalle->producto_precio;
            $this->detalles_devolucion[$posdetalle->producto_codigo]['producto_cantidad'] = $posdetalle->producto_cantidad;
            $this->detalles_devolucion[$posdetalle->producto_codigo]['producto_importe'] = $posdetalle->producto_importe;
            $this->detalles_devolucion[$posdetalle->producto_codigo]['producto_tipo'] = $posdetalle->producto_tipo;
        }
        $this->actualizar_datos();
    }

    public function actualizar_datos()
    {
        $this->total_pagar = 0;
        $this->total_sin_impuesto = 0;
        if ($this->impuesto_porcentaje == false) {
            $this->impuesto_porcentaje = 0;
        }

        $this->descuento = $this->descuento ? $this->descuento : 0;
        $this->envio = $this->envio ? $this->envio : 0;

        foreach ($this->detalles_devolucion as $key => $det_devolucion) {
            $bposdetalle = PosventaDetalle::find($this->detalles_devolucion[$key]['detalle_id']);
            $bdev = DevolucionDetalle::where('detalle_id',$this->detalles_devolucion[$key]['detalle_id'])->sum('producto_cantidad');
            if($this->detalles_devolucion[$key]['producto_cantidad'] >= ($bposdetalle->producto_cantidad-$bdev))
            {
                $this->detalles_devolucion[$key]['producto_cantidad'] = ($bposdetalle->producto_cantidad-$bdev);
            }
            $this->detalles_devolucion[$key]['producto_importe'] =  $this->detalles_devolucion[$key]['producto_cantidad']*$this->detalles_devolucion[$key]['producto_precio'];
            $this->total_sin_impuesto = $this->total_sin_impuesto+$this->detalles_devolucion[$key]['producto_importe'];
        }

        if ($this->impuesto_porcentaje > 0) {$this->impuesto_monto = ($this->total_sin_impuesto*$this->impuesto_porcentaje)/100;}
        else {$this->impuesto_monto = 0;}
        $this->total_pagar = $this->total_sin_impuesto-$this->impuesto_monto-$this->descuento-$this->envio;
    }

    public function crear_devolucion()
    {
        $this->validate($this->rules);
        $n_devolucion = new Devolucion();
        $n_devolucion->fecha = $this->fecha;
        $n_devolucion->posventa_id =$this->posventa_id;
        $n_devolucion->almacen_id = $this->almacen_id;
        $n_devolucion->almacen_name = $this->almacen_name;
        $n_devolucion->cliente_id = $this->cliente_id;
        $n_devolucion->cliente_name = $this->cliente_name;
        $n_devolucion->impuesto_porcentaje = $this->impuesto_porcentaje;
        $n_devolucion->impuesto_monto = $this->impuesto_monto;
        $n_devolucion->descuento = $this->descuento;
        $n_devolucion->envio = $this->envio;
        $n_devolucion->total_pagar = $this->total_pagar;
        $n_devolucion->cantidad_recibida = $this->cantidad_recibida;
        $n_devolucion->monto_pago = $this->total_pagar;
        $n_devolucion->cambio = 0;
        $n_devolucion->nota_devolucion = $this->nota_devolucion;
        $n_devolucion->productos_totales = count($this->detalles_devolucion);
        $n_devolucion->estado_devolucion = 2;
        $n_devolucion->save();

        foreach ($this->detalles_devolucion as $key => $det_dev) {
            $n_det_dev = new DevolucionDetalle();
            $n_det_dev->devolucion_id = $n_devolucion->id;
            $n_det_dev->detalle_id = $this->detalles_devolucion[$key]['detalle_id'];
            $n_det_dev->producto_id = $this->detalles_devolucion[$key]['producto_id'];
            $n_det_dev->producto_codigo = $this->detalles_devolucion[$key]['producto_codigo'];
            $n_det_dev->producto_nombre = $this->detalles_devolucion[$key]['producto_nombre'];
            $n_det_dev->producto_precio = $this->detalles_devolucion[$key]['producto_precio'];
            $n_det_dev->producto_cantidad = $this->detalles_devolucion[$key]['producto_cantidad'];
            $n_det_dev->producto_importe = $this->detalles_devolucion[$key]['producto_importe'];
            $n_det_dev->producto_tipo = $this->detalles_devolucion[$key]['producto_tipo'];
            $n_det_dev->save();
            $this->agregar_stock_almacen($n_det_dev->producto_id,$n_det_dev->producto_cantidad,$n_devolucion->almacen_id);
        }

        $this->almacenform = new AlmacenForm();
        $this->movimientoform = new MovimientoForm();
        $saldo = $this->almacenform->agregar_descontar_monto_almacen($this->almacen_id,$this->total_pagar,'-');
        $this->movimientoform->agregar_movimiento($n_devolucion->id,$this->almacen_id,$this->total_pagar,$saldo,'-','App\Models\Devolucion','crear');
    }

    public function agregar_stock_almacen($producto_id,$cantidad,$almacen_id)
    {
        $b_almacen_producto = ProductoAlmacen::where('producto_id',$producto_id)->where('almacen_id',$almacen_id)->first();
        if ($b_almacen_producto){$b_almacen_producto->stock = $b_almacen_producto->stock+$cantidad;}
        else{
            $b_almacen_producto = new ProductoAlmacen();
            $b_almacen_producto->almacen_id = $almacen_id;
            $b_almacen_producto->producto_id = $producto_id;
            $b_almacen_producto->stock = $cantidad;
        }
        $b_almacen_producto->save();
    }

    public function eliminar_item_devolucion($item_id)
    {
        unset($this->detalles_devolucion[$item_id]);
        $this->actualizar_datos();
    }

    public function descargar_pdf(Devolucion $devolucion)
    {
            $paper_examen = 0;
            $paper_heigth = 430;
            $paper_heigth = $paper_examen + $paper_heigth;
            $configuracion = Configuracion::find(1);
            $nombre_archivo = 'devolucion-' . date("F j, Y, g:i a") . '.pdf';
            $consultapdf = FacadePdf::loadView('administrador.pdf.devolucion', compact('devolucion', 'configuracion'))->setPaper([0, 0, 215.25, $paper_heigth + 18.2 * 2 * $devolucion->devoluciondetalles->count()]);
            $pdfContent = $consultapdf->output();
            return response()->streamDownload(
                fn () => print($pdfContent),
                $nombre_archivo
            );
    }
}
