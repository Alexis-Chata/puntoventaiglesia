<?php

namespace App\Livewire\Forms;

use App\Exports\ReporteComprasExport;
use App\Models\Compra;
use App\Models\Configuracion;
use App\Models\Dcompra;
use App\Models\Dcompracompuesto;
use App\Models\PagoCompra;
use App\Models\Producto;
use App\Models\ProductoAlmacen;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use DateTime;
use Illuminate\Support\Facades\DB;

class ComprasForm extends Form
{
    public ?Compra $compra;
    public $fecha;
    public $prove;
    public $almacen;
    public $estado;
    public $total_sin_impuesto = 0.00;
    public $impuesto_orden = 0.00;
    public $impuesto_orden_monto = 0.00;
    public $descuento;
    public $envio;
    public $total;
    public $pagado;
    public $debido;
    public $nota;
    public $detalle_compra = [];
    public $item_compra_id;

    public function descargar_reporte_compras_excel($compras){
        return Excel::download(new ReporteComprasExport($compras), 'ReporteCompras.xlsx');
    }

    public function descargar_reporte_compras_pdf($compras){
        $configuracion = Configuracion::find(1);
        $nombre_archivo = 'ReporteDeCompras-' . date("F j, Y, g:i a") . '.pdf';
        $consultapdf = FacadePdf::loadView('administrador.compras.reporte_compras_pdf', compact('compras', 'configuracion'))->setPaper('a4', 'landscape');
        $pdfContent = $consultapdf->output();
        return response()->streamDownload(
            fn () => print($pdfContent),
            $nombre_archivo
        );
    }

    public function set(Compra $compra){
        $this->compra = $compra;
        $this->fecha = $compra->fecha;
        $this->prove = $compra->proveedor_id;
        $this->almacen = $compra->almacen_id;
        $this->impuesto_orden = $compra->porcentaje_impuesto_orden;
        $this->total_sin_impuesto = $compra->total_sin_impuesto;
        $this->impuesto_orden_monto = $compra->monto_impuesto_orden;
        $this->descuento = $compra->monto_descuento;
        $this->envio = $compra->monto_envio;
        $this->total = $compra->total;
        $this->estado = $compra->estado;
        $this->nota = $compra->nota;
        $this->total = $compra->debido;

        foreach ($compra->dcompras as $key => $dcompra) {
            $this->detalle_compra[$dcompra->codigo]['id'] = $dcompra->id;
            $this->detalle_compra[$dcompra->codigo]['metodo_descuento'] = $dcompra->metodo_descuento;
            $this->detalle_compra[$dcompra->codigo]['metodo_impuesto']  = $dcompra->metodo_impuesto;
            $this->detalle_compra[$dcompra->codigo]['impuesto_orden']   = $dcompra->impuesto_orden;
            $this->detalle_compra[$dcompra->codigo]['costo'] = $dcompra->costo;
            $this->detalle_compra[$dcompra->codigo]['fecha_vencimiento_producto'] = $dcompra->fecha_vencimiento_producto;
            $bproducto = Producto::find($dcompra->producto_id);
            $this->detalle_compra[$dcompra->codigo]['stock_actual'] = $this->obtener_stock_producto($bproducto);
            $this->detalle_compra[$dcompra->codigo]['compra_unidad'] = $dcompra->compra_unidad;
            $this->detalle_compra[$dcompra->codigo]['descuento_unitario'] = $dcompra->descuento_unitario;
            $this->detalle_compra[$dcompra->codigo]['nombre_producto'] = $dcompra->nombre_producto;
            $this->detalle_compra[$dcompra->codigo]['cantidad'] = $dcompra->cantidad;
            $this->detalle_compra[$dcompra->codigo]['costo_unitario'] = $dcompra->costo_unitario;
            $this->detalle_compra[$dcompra->codigo]['descuento'] = $dcompra->descuento;
            $this->detalle_compra[$dcompra->codigo]['precio'] = $bproducto->precio;
            $this->detalle_compra[$dcompra->codigo]['impuesto'] = $dcompra->impuesto;
            $this->detalle_compra[$dcompra->codigo]['total_parcial'] = $dcompra->total_parcial;
            $this->detalle_compra[$dcompra->codigo]['producto_id'] = $dcompra->producto_id;
        }
    }

    public function update(){
        $this->validate(
            [
                'fecha' => 'required',
                'prove' => 'required',
                'almacen' => 'required',
                'estado' => 'required',
                'detalle_compra' => 'required',
                ]
        );

        #eliminar el stock de la compra
        if ($this->compra->estado == 1) { $this->eliminar_stock_compra($this->compra);}

        $guardar_almacen_anterior = $this->compra->almacen_id;
        if($this->compra->almacen_id != $this->almacen) {
            $this->eliminar_dcompra_compra($this->compra);
        }

        $this->compra->fecha = $this->fecha;
        $this->compra->proveedor_id= $this->prove;
        $this->compra->almacen_id = $this->almacen;
        $this->compra->porcentaje_impuesto_orden = $this->impuesto_orden;
        $this->compra->total_sin_impuesto = $this->total_sin_impuesto;
        $this->compra->monto_impuesto_orden = $this->impuesto_orden_monto;
        $this->compra->monto_descuento = $this->descuento;
        $this->compra->monto_envio = $this->envio;
        $this->compra->total = $this->total;
        $this->compra->estado = $this->estado;
        $this->compra->nota =  $this->nota;
        $this->compra->debido = $this->total;
        $this->compra->save();

        if($guardar_almacen_anterior != $this->almacen) {
            $this->generar_dcompra_compra($this->compra->id);
        }

        else {
            $this->actualizar_dcompra_compra();
        }

        $consultar_compra = Compra::find($this->compra->id);
        if ($this->compra->estado == 1) { $this->agregar_stock_compra($consultar_compra);}
    }

    public function actualizar_dcompra_compra(){
        foreach ($this->compra->dcompras as $key => $dcompra)
        {
            #verificar si el detalle existe en el array
            if (isset($this->detalle_compra[$dcompra->codigo]) == true) {
                $cantidad_anterior = $dcompra->cantidad;
                $dcompra->metodo_descuento = $this->detalle_compra[$dcompra->codigo]['metodo_descuento'];
                $dcompra->metodo_impuesto = $this->detalle_compra[$dcompra->codigo]['metodo_impuesto'];
                $dcompra->impuesto_orden = $this->detalle_compra[$dcompra->codigo]['impuesto_orden'];
                $dcompra->costo = $this->detalle_compra[$dcompra->codigo]['costo'];
                $dcompra->compra_unidad = $this->detalle_compra[$dcompra->codigo]['compra_unidad'];
                $dcompra->descuento_unitario = $this->detalle_compra[$dcompra->codigo]['descuento_unitario'];
                $dcompra->nombre_producto = $this->detalle_compra[$dcompra->codigo]['nombre_producto'];
                $dcompra->fecha_vencimiento_producto = $this->detalle_compra[$dcompra->codigo]['fecha_vencimiento_producto'];
                $dcompra->tipo_producto = Producto::find($this->detalle_compra[$dcompra->codigo]['producto_id'])->tipo;
                $dcompra->cantidad = $this->detalle_compra[$dcompra->codigo]['cantidad'];
                $dcompra->costo_unitario = $this->detalle_compra[$dcompra->codigo]['costo_unitario'];
                $dcompra->stock_actual = $this->detalle_compra[$dcompra->codigo]['stock_actual'];
                $dcompra->descuento = $this->detalle_compra[$dcompra->codigo]['descuento'];
                $dcompra->impuesto = $this->detalle_compra[$dcompra->codigo]['impuesto'];
                $dcompra->total_parcial = $this->detalle_compra[$dcompra->codigo]['total_parcial'];
                $dcompra->producto_id = $this->detalle_compra[$dcompra->codigo]['producto_id'];
                $dcompra->save();
                $this->eliminar_dcompra_dcompracompuesto($dcompra);
                #si el producto es compuesto crear su historial para modificar
                $cproducto = Producto::find($dcompra->producto_id);
                $this->agregar_dcompra_dcompracompuesto($dcompra,$cproducto);
            }
            else {
                $this->eliminar_dcompra($dcompra);
            }
        }
    }

    public function generar_dcompra_compra($compra_id)
    {
        foreach ($this->detalle_compra as $key => $dcompra) {
            $n_dcompra = new Dcompra();
            $n_dcompra->metodo_descuento = $this->detalle_compra[$key]['metodo_descuento'];
            $n_dcompra->metodo_impuesto = $this->detalle_compra[$key]['metodo_impuesto'];
            $n_dcompra->impuesto_orden = $this->detalle_compra[$key]['impuesto_orden'];
            $n_dcompra->costo = $this->detalle_compra[$key]['costo'];
            $n_dcompra->compra_unidad = $this->detalle_compra[$key]['compra_unidad'];
            $n_dcompra->descuento_unitario = $this->detalle_compra[$key]['descuento_unitario'];
            $n_dcompra->nombre_producto = $this->detalle_compra[$key]['nombre_producto'];
            $n_dcompra->fecha_vencimiento_producto = $this->detalle_compra[$key]['fecha_vencimiento_producto'];
            $n_dcompra->tipo_producto = Producto::find($this->detalle_compra[$key]['producto_id'])->tipo;
            $n_dcompra->cantidad = $this->detalle_compra[$key]['cantidad'];
            $n_dcompra->costo_unitario = str_replace(',','',$this->detalle_compra[$key]['costo_unitario']);
            $n_dcompra->stock_actual = $this->detalle_compra[$key]['stock_actual'];
            $n_dcompra->descuento = $this->detalle_compra[$key]['descuento'];
            $n_dcompra->impuesto = $this->detalle_compra[$key]['impuesto'];
            $n_dcompra->total_parcial = $this->detalle_compra[$key]['total_parcial'];
            $n_dcompra->codigo = $key;
            $n_dcompra->producto_id = $this->detalle_compra[$key]['producto_id'];
            $n_dcompra->compra_id = $compra_id;
            $n_dcompra->save();
            if ($n_dcompra->tipo_producto == 'compuesta')
            {
                $bproducto = Producto::find($this->detalle_compra[$key]['producto_id']);
                $this->agregar_dcompra_dcompracompuesto($n_dcompra,$bproducto);
            }
        }
    }

    public function agregar_stock_compra(Compra $compra){
        foreach ($compra->dcompras as $key => $dcompra) {
            $this->agregar_stock_almacen($dcompra->producto_id,$dcompra->cantidad,$compra->almacen_id,$dcompra->fecha_vencimiento_producto);
        }
    }

    public function eliminar_stock_compra(Compra $compra){
        foreach ($compra->dcompras as $key => $dcompra) {
            if ($dcompra->tipo_producto == 'estandar')
            {
                $this->quitar_stock_almacen($dcompra->producto_id,$dcompra->cantidad,$compra->almacen_id);
            }
            elseif($dcompra->tipo_producto == 'compuesto')
            {
                foreach ($dcompra->dcompracompuestos as $key => $dcomcom) {
                    $this->quitar_stock_almacen($dcomcom->producto_asignado_id,$dcompra->cantidad*$dcomcom->cantidad,$compra->almacen_id);
                }
            }
        }
    }

    public function eliminar_dcompra_compra(Compra $compra)
    {
        foreach ($compra->dcompras as $key => $dcompra)
        {
            if ($dcompra->tipo_producto == 'compuesto')
                {
                    foreach ($dcompra->dcompracompuestos as $key => $dcomp) {$dcomp->delete();}
                }
            $dcompra->delete();
        }
    }

    public function eliminar_dcompra(Dcompra $dcompra)
    {
        if ($dcompra->tipo_producto == 'compuesto')
        {foreach ($dcompra->dcompracompuestos as $key => $dcomp) {$dcomp->delete();}}
        $dcompra->delete();
    }

    public function agregar_dcompra_dcompracompuesto(Dcompra $dcompra,Producto $producto)
    {
        if ($producto->tipo == 'compuesto') {
            foreach ($producto->pcompuestos as $rey => $pcom)
            {
                $n_dcompra_compuesto = new Dcompracompuesto();
                $n_dcompra_compuesto->dcompra_id = $dcompra->id;
                $n_dcompra_compuesto->producto_asignado_id = $pcom->producto_asignado_id;
                $n_dcompra_compuesto->cantidad = $pcom->cantidad;
                $n_dcompra_compuesto->save();
            }
        }
    }

    public function eliminar_dcompra_dcompracompuesto(Dcompra $dcompra)
    {
        if ($dcompra->tipo_producto == 'compuesto')
        {foreach ($dcompra->dcompracompuestos as $key => $dcomp) {$dcomp->delete();}}
    }

    public function eliminar_compra()
    {
        $this->eliminar_dcompra_compra($this->compra);
        $this->compra->delete();
    }

    public function store()
    {
        $this->validate(
            [
                'fecha' => 'required',
                'prove' => 'required',
                'almacen' => 'required',
                'estado' => 'required',
                'detalle_compra' => 'required',
                ]
        );
        #crear compra
        $n_compra = new Compra();
        $n_compra->fecha = $this->fecha;
        $n_compra->proveedor_id= $this->prove;
        $n_compra->almacen_id = $this->almacen;
        $n_compra->porcentaje_impuesto_orden = $this->impuesto_orden;
        $n_compra->total_sin_impuesto = $this->total_sin_impuesto;
        $n_compra->monto_impuesto_orden = $this->impuesto_orden_monto;
        $n_compra->monto_descuento = $this->descuento;
        $n_compra->monto_envio = $this->envio;
        $n_compra->total = $this->total;
        $n_compra->estado = $this->estado;
        $n_compra->nota =  $this->nota;
        $n_compra->debido = $this->total;
        $n_compra->save();
        foreach ($this->detalle_compra as $key => $dcompra) {
            $n_dcompra = new Dcompra();
            $n_dcompra->metodo_descuento = $this->detalle_compra[$key]['metodo_descuento'];
            $n_dcompra->metodo_impuesto = $this->detalle_compra[$key]['metodo_impuesto'];
            $n_dcompra->impuesto_orden = $this->detalle_compra[$key]['impuesto_orden'];
            $n_dcompra->costo = $this->detalle_compra[$key]['costo'];
            $n_dcompra->compra_unidad = $this->detalle_compra[$key]['compra_unidad'];
            $n_dcompra->descuento_unitario = $this->detalle_compra[$key]['descuento_unitario'];
            $n_dcompra->nombre_producto = $this->detalle_compra[$key]['nombre_producto'];
            $n_dcompra->fecha_vencimiento_producto = $this->detalle_compra[$key]['fecha_vencimiento_producto'];
            $n_dcompra->cantidad = $this->detalle_compra[$key]['cantidad'];
            $n_dcompra->costo_unitario = str_replace(',','',$this->detalle_compra[$key]['costo_unitario']);
            $n_dcompra->stock_actual = $this->detalle_compra[$key]['stock_actual'];
            $n_dcompra->descuento = $this->detalle_compra[$key]['descuento'];
            $n_dcompra->impuesto = $this->detalle_compra[$key]['impuesto'];
            $n_dcompra->total_parcial = $this->detalle_compra[$key]['total_parcial'];
            $n_dcompra->codigo = $key;
            $n_dcompra->producto_id = $this->detalle_compra[$key]['producto_id'];
            $n_dcompra->compra_id = $n_compra->id;
            $n_dcompra->save();
            #si el producto es compuesto crear su historial para modificar
            $cproducto = Producto::find($n_dcompra->producto_id);
            $this->agregar_dcompra_dcompracompuesto($n_dcompra,$cproducto);


            if ($n_compra->estado == 1) {
                $this->agregar_stock_almacen($this->detalle_compra[$key]['producto_id'],$n_dcompra->cantidad,$n_compra->almacen_id,$n_dcompra->fecha_vencimiento_producto);
            }
        }
    }

    public function agregar_stock_almacen($producto_id,$cantidad,$almacen_id,$fecha_vencimiento = null){
        $bproducto = Producto::find($producto_id);
        if ($bproducto->tipo == 'estandar')
        {
            $b_almacen_producto = ProductoAlmacen::where('producto_id',$producto_id)->where('almacen_id',$almacen_id)->first();
            if ($b_almacen_producto)
            {
                $b_almacen_producto->stock = $b_almacen_producto->stock+$cantidad;

                if ($b_almacen_producto->fecha_vencimiento_producto == true)
                {
                    if (strtotime($fecha_vencimiento) > strtotime($b_almacen_producto->fecha_vencimiento_producto) )
                    {
                        $b_almacen_producto->fecha_vencimiento_producto = $fecha_vencimiento;
                    }
                }
                else
                {
                    $b_almacen_producto->fecha_vencimiento_producto = $fecha_vencimiento;
                }
            }
            else
            {
                $b_almacen_producto = new ProductoAlmacen();
                $b_almacen_producto->almacen_id = $almacen_id;
                $b_almacen_producto->producto_id = $producto_id;
                $b_almacen_producto->stock = $cantidad;
                $b_almacen_producto->fecha_vencimiento_producto = $fecha_vencimiento;
            }
            $b_almacen_producto->save();
            }
        elseif($bproducto->tipo == 'compuesto')
        {
            foreach ($bproducto->pcompuestos as $ley => $tcomp) {
                $b_almacen_producto = ProductoAlmacen::where('producto_id',$tcomp->producto_asignado_id)->where('almacen_id',$almacen_id)->first();
                if ($b_almacen_producto){
                    $b_almacen_producto->stock = $b_almacen_producto->stock+($cantidad*$tcomp->cantidad);
                    if ($b_almacen_producto->fecha_vencimiento_producto == true)
                    {
                        if (strtotime($fecha_vencimiento) > strtotime($b_almacen_producto->fecha_vencimiento_producto) )
                        {
                            $b_almacen_producto->fecha_vencimiento_producto = $fecha_vencimiento;
                        }
                    }
                    else {
                        $b_almacen_producto->fecha_vencimiento_producto = $fecha_vencimiento;
                    }
                }
                else
                {
                    $b_almacen_producto = new ProductoAlmacen();
                    $b_almacen_producto->almacen_id = $almacen_id;
                    $b_almacen_producto->producto_id = $tcomp->producto_asignado_id;
                    $b_almacen_producto->stock = $cantidad*$tcomp->cantidad;
                    $b_almacen_producto->fecha_vencimiento_producto = $fecha_vencimiento;
                }
                $b_almacen_producto->save();
            }
        }
    }

    public function quitar_stock_almacen($producto_id,$cantidad,$almacen_id){
        $bproducto = Producto::find($producto_id);
        if ($bproducto->tipo == 'estandar')
        {
            $b_almacen_producto = ProductoAlmacen::where('producto_id',$producto_id)->where('almacen_id',$almacen_id)->first();
            if ($b_almacen_producto){$b_almacen_producto->stock = $b_almacen_producto->stock-$cantidad;}
            $b_almacen_producto->save();
        }

        elseif($bproducto->tipo == 'compuesto')
        {
            foreach ($bproducto->pcompuestos as $ley => $tcomp)
            {
                $b_almacen_producto = ProductoAlmacen::where('producto_id',$tcomp->producto_asignado_id)->where('almacen_id',$almacen_id)->first();
                if ($b_almacen_producto){$b_almacen_producto->stock = $b_almacen_producto->stock-($cantidad*$tcomp->cantidad);}
                $b_almacen_producto->save();
            }
        }
    }

    public function actualizar_stock_almacen($producto_id,$cantidad_anterior,$cantidad_actual,$almacen_id)
    {
        $bproducto = Producto::find($producto_id);
        if ($bproducto->tipo == 'estandar')
        {
            $b_almacen_producto = ProductoAlmacen::where('producto_id',$producto_id)->where('almacen_id',$almacen_id)->first();
            if ($b_almacen_producto){$b_almacen_producto->stock = $b_almacen_producto->stock-$cantidad_anterior+$cantidad_actual;}
            $b_almacen_producto->save();
        }
        elseif($bproducto->tipo == 'compuesto')
        {
            foreach ($bproducto->pcompuestos as $ley => $tcomp)
            {
                $b_almacen_producto = ProductoAlmacen::where('producto_id',$tcomp->producto_asignado_id)->where('almacen_id',$almacen_id)->first();
                if ($b_almacen_producto){$b_almacen_producto->stock = $b_almacen_producto->stock-($cantidad_anterior*$tcomp->cantidad)+($cantidad_actual*$tcomp->cantidad);}
                $b_almacen_producto->save();
            }
        }
    }

    public function obtener_datos_compra(){
        $this->total_sin_impuesto = 0;
        foreach ($this->detalle_compra as $key => $dcompra){$this->total_sin_impuesto = $this->total_sin_impuesto + $this->detalle_compra[$key]['total_parcial'];}
        $this->obtener_impuesto_orden_monto();
        $this->obtener_total_compra();
    }

    public function obtener_impuesto_orden_monto(){
        if ($this->impuesto_orden) {$this->impuesto_orden_monto = $this->total_sin_impuesto*$this->impuesto_orden/100;}
        else
        {
            $this->impuesto_orden_monto = 0.00;
            $this->impuesto_orden = 0.00;
        }
    }

    public function obtener_total_compra(){
        if ($this->descuento == false) {$this->descuento = 0;}
        if ($this->envio == false) {$this->envio = 0;}
        $this->total = $this->total_sin_impuesto+$this->impuesto_orden_monto-$this->descuento+$this->envio;
    }

    public function eliminar_item_compra($item_id){
        unset($this->detalle_compra[$item_id]);
    }

    public function obtener_stock_producto(Producto $producto)
    {
        if ($producto->tipo == 'compuesto') {
            $stock_productos = [];
            foreach ($producto->pcompuestos as $key => $pcompuesto) {
                $consultar_stock = ProductoAlmacen::where('producto_id',$pcompuesto->producto_asignado_id)->where('almacen_id',$this->almacen)->first();
                $stock_productos[$pcompuesto->producto_id] = $consultar_stock == true ? $consultar_stock->stock/$pcompuesto->cantidad : 0;
            }
            return max($stock_productos);
        }
        elseif($producto->tipo == 'estandar') {
            $consultar_stock = ProductoAlmacen::where('producto_id',$producto->id)->where('almacen_id',$this->almacen)->first();
            return $consultar_stock == true ? $consultar_stock->stock : 0;
        }
    }

    public function agregar_item(Producto $producto)
    {
        $this->detalle_compra[$producto->codigo]['producto_id'] = $producto->id;
        $this->detalle_compra[$producto->codigo]['metodo_descuento'] = 'fijado';
        $this->detalle_compra[$producto->codigo]['metodo_impuesto'] = 'exclusivo';
        $this->detalle_compra[$producto->codigo]['impuesto_orden'] = $producto->impuesto_orden;
        $this->detalle_compra[$producto->codigo]['costo'] = $producto->costo;
        $this->detalle_compra[$producto->codigo]['precio'] = $producto->precio;
        $this->detalle_compra[$producto->codigo]['compra_unidad'] = 'unidad';
        $this->detalle_compra[$producto->codigo]['descuento_unitario'] = 0;
        $this->detalle_compra[$producto->codigo]['nombre_producto'] = $producto->designacion;
        if ($this->detalle_compra[$producto->codigo]['metodo_impuesto'] == 'exclusivo') {
            $this->detalle_compra[$producto->codigo]['costo_unitario'] = number_format(($this->detalle_compra[$producto->codigo]['costo']-$this->detalle_compra[$producto->codigo]['descuento_unitario']),3);
        }
        elseif($this->detalle_compra[$producto->codigo]['metodo_impuesto'] == 'inclusivo') {
            $this->detalle_compra[$producto->codigo]['costo_unitario'] = number_format(($this->detalle_compra[$producto->codigo]['costo']-$this->detalle_compra[$producto->codigo]['descuento_unitario'])/(($this->detalle_compra[$producto->codigo]['impuesto_orden']+100)/100),3);
        }

        $this->detalle_compra[$producto->codigo]['stock_actual'] =  $this->obtener_stock_producto($producto);
        $this->detalle_compra[$producto->codigo]['fecha_vencimiento_producto'] = date('Y-m-d');

        $this->detalle_compra[$producto->codigo]['cantidad'] = 1;
        $this->detalle_compra[$producto->codigo]['descuento'] = $this->detalle_compra[$producto->codigo]['cantidad']*$this->detalle_compra[$producto->codigo]['descuento_unitario'];
        $this->detalle_compra[$producto->codigo]['impuesto'] =  number_format((((str_replace(',','',$this->detalle_compra[$producto->codigo]['costo_unitario'])-str_replace(',','',$this->detalle_compra[$producto->codigo]['descuento_unitario']))*str_replace(',','',$this->detalle_compra[$producto->codigo]['cantidad']))*str_replace(',','',$producto->impuesto_orden)/100),2);
        $this->detalle_compra[$producto->codigo]['total_parcial'] = str_replace(',','',$this->detalle_compra[$producto->codigo]['costo_unitario'])*$this->detalle_compra[$producto->codigo]['cantidad']+$this->detalle_compra[$producto->codigo]['impuesto'];
    }

    public function actualizar_item
    (
        $item_id,$item_costo_producto,$item_metodo_impuesto,$item_impuesto_orden,$item_metodo_descuento,$item_descuento,$item_compra_unidad,
        $item_cantidad,$item_nombre_producto,$item_producto_id,$item_precio_producto
    )
    {
        $this->detalle_compra[$item_id]['producto_id']        = $item_producto_id;
        $this->detalle_compra[$item_id]['metodo_descuento']   = $item_metodo_descuento;
        $this->detalle_compra[$item_id]['metodo_impuesto']    = $item_metodo_impuesto;
        $this->detalle_compra[$item_id]['impuesto_orden']     = $item_impuesto_orden ? $item_impuesto_orden : 0;
        $this->detalle_compra[$item_id]['costo']              = $item_costo_producto ? $item_costo_producto : 1;
        $this->detalle_compra[$item_id]['precio']              = $item_precio_producto ? $item_precio_producto : 1;
        $this->detalle_compra[$item_id]['compra_unidad']      = $item_compra_unidad;
        $this->detalle_compra[$item_id]['descuento_unitario'] = $item_descuento ? $item_descuento : 0;
        $this->detalle_compra[$item_id]['nombre_producto'] = $item_nombre_producto;

        $bproducto = Producto::find($item_producto_id);
        $this->detalle_compra[$item_id]['stock_actual'] =  $this->obtener_stock_producto($bproducto);

        $this->detalle_compra[$item_id]['cantidad'] = $item_cantidad == false ? 1 : $item_cantidad;

        if ($this->detalle_compra[$item_id]['metodo_impuesto'] == 'exclusivo') {
            $this->detalle_compra[$item_id]['costo_unitario'] = number_format(($this->detalle_compra[$item_id]['costo']-$this->detalle_compra[$item_id]['descuento_unitario']),3);
        }

        elseif($this->detalle_compra[$item_id]['metodo_impuesto'] == 'inclusivo') {
            $this->detalle_compra[$item_id]['costo_unitario'] = number_format(($this->detalle_compra[$item_id]['costo']-$this->detalle_compra[$item_id]['descuento_unitario'])/(($this->detalle_compra[$item_id]['impuesto_orden']+100)/100),3);
        }

        $this->detalle_compra[$item_id]['descuento'] = $this->detalle_compra[$item_id]['cantidad']*$this->detalle_compra[$item_id]['descuento_unitario'];
        $this->detalle_compra[$item_id]['impuesto'] =  number_format(((( str_replace(',','',$this->detalle_compra[$item_id]['costo_unitario'])-$this->detalle_compra[$item_id]['descuento_unitario'])*$this->detalle_compra[$item_id]['cantidad'])*$this->detalle_compra[$item_id]['impuesto_orden']/100),2);
        $this->detalle_compra[$item_id]['total_parcial'] = str_replace(',','',$this->detalle_compra[$item_id]['costo_unitario'])*$this->detalle_compra[$item_id]['cantidad']+str_replace(',','',$this->detalle_compra[$item_id]['impuesto']);
        $this->obtener_datos_compra();
    }

    public function obtener_productos_compras_dias($fecha_inicia,$fecha_final,$almacen = null){
        $compras = [];
        $fecha1= new DateTime($fecha_inicia);
        $fecha2= new DateTime($fecha_final);
        $diff = $fecha1->diff($fecha2);
        $dias = $diff->days;

        $dia_actual = $fecha_inicia;
        for ($i=0; $i <= $dias; $i++)
        {
            if ($almacen <> null) {
                $compras[] =  PagoCompra::where('fecha_pago','>=',$dia_actual)->where('fecha_pago','<=',$dia_actual)->whereExists(function ($query) use ($almacen)  {
                    $query->select()
                          ->from(DB::raw('compras'))
                          ->whereColumn('pago_compras.compra_id', 'compras.id')
                          ->where('compras.almacen_id',$almacen);
                })->sum('monto_pago');
            }
            else {
                $compras[] =  PagoCompra::where('fecha_pago','>=',$dia_actual)->where('fecha_pago','<=',$dia_actual)->sum('monto_pago');
            }
            $dia_actual = strtotime('+1 day', strtotime($dia_actual));
            $dia_actual = date('Y-m-d', $dia_actual);
        }
        return $compras;
    }
}
