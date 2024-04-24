<?php

namespace App\Livewire;

use App\Exports\ReporteComprasExport;
use App\Livewire\Forms\ComprasForm;
use App\Livewire\Forms\ProductoForm;
use App\Livewire\Forms\PagoCompraForm;
use App\Models\Almacen;
use App\Models\Compra;
use App\Models\Configuracion;
use App\Models\PagoCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class GestionarCompras extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public ComprasForm $comprasform;
    public ProductoForm $productoform;
    public PagoCompraForm $pagocompraform;
    public $search = '';
    public $titlemodal = 'Añadir';
    public $titlemodal_pagocompra = 'Añadir';
    public $sel_almacen;
    public $pagina = 5;
    public $buscar_producto = '';
    public $buscar_proveedor = '';
    public $buscar_producto_oculto = '';
    public $buscar_proveedor_oculto = '';
    public $search2;
    public $referencia_compra_id;
    public $iteration=0;
    public $editar_item_id;
    public $editar_item = false;
    public $configuracion;
    public $finicio,$ffinal,$salmacen;
    #editar item
    public $item_precio_producto,$item_costo_producto,$item_metodo_impuesto,$item_impuesto_orden,$item_metodo_descuento,$item_descuento,$item_compra_unidad,$item_producto_id;
    public $item_cantidad,$item_nombre_producto;
    public function mount(){  $this->configuracion = Configuracion::find(1); }

    public function save_compra(){
        $bproveedor = Proveedor::find($this->buscar_proveedor_oculto);
        $this->comprasform->prove =  $bproveedor ? $this->buscar_proveedor_oculto : null;
        if (isset($this->comprasform->compra)) {$this->comprasform->update();}

        else {$this->comprasform->store();}
        $this->dispatch('cerrar_modal_compra');
    }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function actualizar_item_compra($item_id)
    {
        $this->editar_item_id = $item_id;
        $this->item_costo_producto   = $this->comprasform->detalle_compra[$item_id]['costo'];
        $this->item_precio_producto   = $this->comprasform->detalle_compra[$item_id]['precio'];
        $this->item_metodo_impuesto  = $this->comprasform->detalle_compra[$item_id]['metodo_impuesto'];
        $this->item_impuesto_orden   = $this->comprasform->detalle_compra[$item_id]['impuesto_orden'];
        $this->item_metodo_descuento = $this->comprasform->detalle_compra[$item_id]['metodo_descuento'];
        $this->item_descuento        = $this->comprasform->detalle_compra[$item_id]['descuento_unitario'];
        $this->item_compra_unidad    = $this->comprasform->detalle_compra[$item_id]['compra_unidad'];
        $this->item_cantidad         = $this->comprasform->detalle_compra[$item_id]['cantidad'];
        $this->item_nombre_producto  = $this->comprasform->detalle_compra[$item_id]['nombre_producto'];
        $this->item_producto_id  = $this->comprasform->detalle_compra[$item_id]['producto_id'];
        $this->comprasform->actualizar_item($this->editar_item_id,
            $this->item_costo_producto,
            $this->item_metodo_impuesto,
            $this->item_impuesto_orden,
            $this->item_metodo_descuento,
            $this->item_descuento,
            $this->item_compra_unidad,
            $this->item_cantidad,
            $this->item_nombre_producto,
            $this->item_producto_id,
            $this->item_precio_producto
        );
        $this->editar_item = false;
    }

    public function updatedComprasform()
    {
        foreach ($this->comprasform->detalle_compra as $kadc => $dcompra)
        {
            $this->actualizar_item_compra($kadc);
        }
        $this->comprasform->obtener_datos_compra();
    }

    public function editar_item_compra($item_id)
    {
        $this->editar_item = true;
        $this->editar_item_id = $item_id;
        $this->item_costo_producto   = $this->comprasform->detalle_compra[$item_id]['costo'];
        $this->item_precio_producto   = $this->comprasform->detalle_compra[$item_id]['precio'];
        $this->item_metodo_impuesto  = $this->comprasform->detalle_compra[$item_id]['metodo_impuesto'];
        $this->item_impuesto_orden   = $this->comprasform->detalle_compra[$item_id]['impuesto_orden'];
        $this->item_metodo_descuento = $this->comprasform->detalle_compra[$item_id]['metodo_descuento'];
        $this->item_descuento        = $this->comprasform->detalle_compra[$item_id]['descuento_unitario'];
        $this->item_compra_unidad    = $this->comprasform->detalle_compra[$item_id]['compra_unidad'];
        $this->item_cantidad         = $this->comprasform->detalle_compra[$item_id]['cantidad'];
        $this->item_nombre_producto  = $this->comprasform->detalle_compra[$item_id]['nombre_producto'];
        $this->item_producto_id      = $this->comprasform->detalle_compra[$item_id]['producto_id'];
    }

    public function modificar_item(){
        $this->comprasform->actualizar_item(
            $this->editar_item_id,
            $this->item_costo_producto,
            $this->item_metodo_impuesto,
            $this->item_impuesto_orden,
            $this->item_metodo_descuento,
            $this->item_descuento,
            $this->item_compra_unidad,
            $this->item_cantidad,
            $this->item_nombre_producto,
            $this->item_producto_id,
            $this->item_precio_producto,
        );
        $this->editar_item = false;
        $bproducto = Producto::find($this->item_producto_id);
        $this->productoform->actualizar_costo_producto($bproducto,$this->item_costo_producto);
        $this->productoform->actualizar_precio_producto($bproducto,$this->item_precio_producto);
    }



    public function updatedBuscarProductoOculto()
    {
        $bproducto = Producto::where('codigo',$this->buscar_producto_oculto)->first();
        if ($bproducto)
        {
            $this->comprasform->agregar_item($bproducto);
            $this->comprasform->obtener_datos_compra();
        }
    }

    public function updatedBuscarProducto()
    {
        $this->iteration++;
        $valmacen = Almacen::find($this->comprasform->almacen);
        if ($valmacen) {
            $this->dispatch('activar_buscador_producto');
            $bproducto = Producto::where('codigo',$this->buscar_producto)->first();
            if ($bproducto) {
                $this->comprasform->agregar_item($bproducto);
                $this->comprasform->obtener_datos_compra();
            }
        }
        else {$this->dispatch('advertencia_almacen'); }
    }

    public function updatedBuscarProveedor()
    {
        $this->iteration++;
        $this->dispatch('activar_buscador_proveedor');

        $bproveedor = Proveedor::where('name',$this->buscar_proveedor)->first();

        if($bproveedor == false){
            $this->buscar_proveedor_oculto = null;
        }
    }

    public function updatedSelAlmacen(){
        $this->comprasform->reset();
    }

    public function eliminar_item_compra($item_id){
        $this->comprasform->eliminar_item_compra($item_id);
        $this->comprasform->obtener_datos_compra();
    }

    public function modal(Compra $compra = null)
    {
        $this->reset('titlemodal','buscar_proveedor','buscar_proveedor_oculto');
        $this->comprasform->reset();
        $this->comprasform->fecha = date('Y-m-d');
        $this->comprasform->almacen = Almacen::find(1) ? Almacen::find(1)->id : null;
        if ($compra->id == true) {
            $this->titlemodal = 'Editar';
            $this->comprasform->set($compra);
            $this->buscar_proveedor_oculto = $this->comprasform->compra->proveedor_id;
            $this->buscar_proveedor = Proveedor::find($this->comprasform->compra->proveedor_id)->name;
            $this->comprasform->fecha = $compra->fecha;
        }
    }

    public function modal_pago_compra(Compra $compra,PagoCompra $pagocompra = null)
    {
        $this->reset('referencia_compra_id');
        $this->pagocompraform->reset();
        $this->pagocompraform->fecha_pago = date('Y-m-d');
        $this->pagocompraform->compra = $compra;
        $this->pagocompraform->cantidad_recibida = $compra->total-$compra->pagocompras->sum('monto_pago');
        $this->pagocompraform->monto_pago = $compra->total-$compra->pagocompras->sum('monto_pago');
        $this->pagocompraform->cambio = 0;
        $this->referencia_compra_id = 'COM_'.$compra->id;
        if ($pagocompra->id == true) {
            $this->titlemodal_pagocompra = 'Editar';
            $this->pagocompraform->set($pagocompra);
        }
    }

    public function guardar_pago(){
        $this->pagocompraform->store();
        $this->dispatch('cerrar_modal_pago_compra');
    }

    public function updatedPagocompraForm(){
        $this->pagocompraform->calcular_campos();
    }

    public function eliminar(Compra $compra){
        $this->comprasform->set($compra);
        $this->comprasform->eliminar_compra();
        $this->comprasform->reset();
        $this->updatedSearch();
    }

    public function eliminar_pago_compra(PagoCompra $pagoCompra){
        $compra_id = $pagoCompra->compra_id;
        $pagoCompra->delete();
        $this->pagocompraform->actualizar_compra($compra_id);
    }

    public function descargar_reporte_compras_excel()
    {
        $compras = Compra::query()->orwhere('id','like',"%".$this->search."%")->whereExists(function ($query)  {
            $query->select()
                  ->from(DB::raw('proveedors'))
                  ->whereColumn('compras.proveedor_id', 'proveedors.id')
                  ->where('proveedors.name','like','%'.$this->search.'%');
        })->orderByDesc('id');

        $compras->when($this->salmacen <> '',function ($q) {
            return $q->where('almacen_id',$this->salmacen);
        });

        $compras->when($this->finicio != null && $this->ffinal != null  ,function ($q) {
            return $q->where('fecha','>=',$this->finicio)->where('fecha','<=',$this->ffinal);
        });

        $compras = $compras->get();
        return $this->comprasform->descargar_reporte_compras_excel($compras);
    }

    public function descargar_reporte_compras_pdf(){
        $compras = Compra::query()->orwhere('id','like',"%".$this->search."%")->whereExists(function ($query)  {
            $query->select()
                  ->from(DB::raw('proveedors'))
                  ->whereColumn('compras.proveedor_id', 'proveedors.id')
                  ->where('proveedors.name','like','%'.$this->search.'%');
        })->orderByDesc('id');

        $compras->when($this->salmacen <> '',function ($q) {
            return $q->where('almacen_id',$this->salmacen);
        });

        $compras->when($this->finicio != null && $this->ffinal != null  ,function ($q) {
            return $q->where('fecha','>=',$this->finicio)->where('fecha','<=',$this->ffinal);
        });

        $compras = $compras->get();
        return $this->comprasform->descargar_reporte_compras_pdf($compras);
    }

    public function render()
    {
        $compras = Compra::query()->orwhere('id','like',"%".$this->search."%")->whereExists(function ($query)  {
            $query->select()
                  ->from(DB::raw('proveedors'))
                  ->whereColumn('compras.proveedor_id', 'proveedors.id')
                  ->where('proveedors.name','like','%'.$this->search.'%');
        })->orderByDesc('id');

        $compras->when($this->salmacen <> '',function ($q) {
            return $q->where('almacen_id',$this->salmacen);
        });

        $compras->when($this->finicio != null && $this->ffinal != null  ,function ($q) {
            return $q->where('fecha','>=',$this->finicio)->where('fecha','<=',$this->ffinal);
        });

        $compras = $compras->paginate($this->pagina);
        $proveedors = Proveedor::all();
        $almacens = Almacen::all();
        return view('livewire.gestionar-compras', compact('compras','proveedors','almacens'));
    }
}
