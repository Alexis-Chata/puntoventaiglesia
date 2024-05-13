<?php

namespace App\Livewire\Forms;

use App\Exports\ReporteProductosExport;
use App\Models\CompuestoProducto;
use App\Models\Configuracion;
use App\Models\Producto;
use App\Models\ProductoAlmacen;
use App\Models\Almacen;
use App\Traits\ImagenTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Form;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class ProductoForm extends Form
{
    use ImagenTrait;

    public ?Producto $producto;

    public $designacion;
    public $simbologia;
    public $codigo;
    public $marca_id = null;
    public $impuesto_orden;
    public $metodo_impuesto = 'exclusivo';
    public $categoria_id;
    public $descripcion;
    public $tipo = 'estandar';
    public $costo;
    public $precio;
    public $unitario;
    public $venta_unidad;
    public $compra_unidad;
    public $alerta_stock = 0;
    public $imagen;
    public $ilimitado;
    public $productos_compuesto = [];
    public $productos_compuesto_total = 0;

    public $regla_producto = [
        'designacion' => 'required',
        'simbologia' => 'required',
        'categoria_id' => 'required',
        'tipo' => 'required',
        'costo' => 'required',
        'unitario' => 'required',
        'venta_unidad' => 'required',
        'compra_unidad' => 'required',
        'precio' => 'required',
        'metodo_impuesto' => 'required',
    ];

    public function descargar_reporte_productos_excel($lista_productos){
        return Excel::download(new ReporteProductosExport($lista_productos), 'ReporteProductos.xlsx');
    }

    public function descargar_reporte_productos_pdf($lista_productos){
        $configuracion = Configuracion::find(1);
        $nombre_archivo = 'ReporteDeProductos-' . date("F j, Y, g:i a") . '.pdf';
        $consultapdf = FacadePdf::loadView('administrador.productos.reporte_productos_pdf', compact('lista_productos', 'configuracion'))->setPaper('a4', 'landscape');
        $pdfContent = $consultapdf->output();
        return response()->streamDownload(
            fn () => print($pdfContent),
            $nombre_archivo
        );
    }

    public function set(Producto $producto)
    {
        $this->producto = $producto;
        $this->designacion = $producto->designacion;
        $this->simbologia = $producto->simbologia;
        $this->codigo = $producto->codigo;
        $this->marca_id = $producto->marca_id;
        $this->impuesto_orden = $producto->impuesto_orden;
        $this->metodo_impuesto = $producto->metodo_impuesto;
        $this->categoria_id = $producto->categoria_id;
        $this->descripcion = $producto->descripcion;
        $this->tipo = $producto->tipo;
        $this->costo = $producto->costo;
        $this->precio = $producto->precio;
        $this->unitario = $producto->unitario;
        $this->venta_unidad = $producto->venta_unidad;
        $this->compra_unidad = $producto->compra_unidad;
        $this->alerta_stock = $producto->alerta_stock;
        $this->ilimitado = $producto->ilimitado;

        if ($producto->tipo == 'compuesto') {
            foreach ($producto->pcompuestos as $key => $pcom)
            {
                $bproducto = Producto::find($pcom->producto_asignado_id);
                $this->productos_compuesto[$bproducto->codigo]['producto_id'] = $bproducto->id;
                $this->productos_compuesto[$bproducto->codigo]['codigo'] = $bproducto->codigo;
                $this->productos_compuesto[$bproducto->codigo]['nombre'] = $bproducto->designacion;
                $this->productos_compuesto[$bproducto->codigo]['precio'] = $bproducto->precio;
                $this->productos_compuesto[$bproducto->codigo]['cantidad'] = $pcom->cantidad;
                $this->productos_compuesto[$bproducto->codigo]['total'] = $this->productos_compuesto[$bproducto->codigo]['precio']*$this->productos_compuesto[$bproducto->codigo]['cantidad'];
            }
            $this->verificar_productos();
        }
    }

    public function actualizar_costo_producto(Producto $producto,$costo_producto){
            $producto->costo = $costo_producto;
            $producto->save();
    }

    public function actualizar_precio_producto(Producto $producto,$precio_producto){
        $producto->precio = $precio_producto;
        $producto->save();
}

    public function updat()
    {

        $this->validate($this->regla_producto+
        ['codigo' => 'required|unique:productos,codigo,'.$this->producto->id]);
        if ($this->tipo == 'compuesto') {
            $this->validate(['productos_compuesto' => 'required']);
        }

        $this->producto->update($this->all());
        if ($this->tipo == 'compuesto') {
            $this->actualizar_dproducto_compuesta();
        }
    }

    public function actualizar_dproducto_compuesta(){

        foreach ($this->producto->pcompuestos as $key => $pcom)
        {
            #verificar si el detalle existe en el array
            if (isset($this->productos_compuesto[$pcom->codigo]) == true) {
                $pcom->producto_asignado_id = $this->productos_compuesto[$pcom->codigo]['producto_id'];
                $pcom->cantidad = $this->productos_compuesto[$pcom->codigo]['cantidad'];
                $pcom->save();
            }
            else {
                $pcom->delete();
            }
        }
    }

    public function store($imagen = null)
    {
        $this->validate($this->regla_producto);
        if ($this->tipo == 'compuesto') {
            $this->validate(['productos_compuesto' => 'required']);
        }
        if ($this->marca_id == false) {
            $this->marca_id = null;
        }

        if ($this->ilimitado == null) {
            $this->ilimitado = false;
        }

        $this->marca_id = ($this->marca_id == false) ? null : $this->marca_id;
        $this->impuesto_orden = ($this->impuesto_orden == false) ? 0 : $this->impuesto_orden;
        (isset($this->producto)) ? $this->updat() : $this->producto = Producto::create($this->all());

        if ($imagen)
        {
            $this->eliminar_imagen($this->producto->imagen);
            $this->producto->imagen = $this->subir_imagen($imagen, $this->producto->id, "producto_img");
            $this->producto->save();
        }


        if ($this->tipo == 'compuesto') {
            foreach ($this->productos_compuesto as $key => $value)
            {
                $new_com_pro = new CompuestoProducto();
                $new_com_pro->producto_id = $this->producto->id;
                $new_com_pro->producto_asignado_id = $this->productos_compuesto[$key]['producto_id'];
                $new_com_pro->cantidad = $this->productos_compuesto[$key]['cantidad'];
                $new_com_pro->save();
            }

            #agregar a los almacenes
            $almacenes = Almacen::all();
            foreach ($almacenes as $tey => $alm) {
                    $ne_pro_alm = new ProductoAlmacen();
                    $ne_pro_alm->almacen_id  = $alm->id;
                    $ne_pro_alm->producto_id = $this->producto->id;
                    $ne_pro_alm->stock = 0;
                    $ne_pro_alm->save();
            }
        }

    }

    public function agregar_producto_compuesto($codigo){
        $bproducto = Producto::where('codigo',$codigo)->first();

        if ($bproducto)
        {
            $this->productos_compuesto[$bproducto->codigo]['producto_id'] = $bproducto->id;
            $this->productos_compuesto[$bproducto->codigo]['codigo'] = $bproducto->codigo;
            $this->productos_compuesto[$bproducto->codigo]['nombre'] = $bproducto->designacion;
            $this->productos_compuesto[$bproducto->codigo]['precio'] = $bproducto->precio;
            $this->productos_compuesto[$bproducto->codigo]['cantidad'] = 1;
            $this->productos_compuesto[$bproducto->codigo]['total'] = $this->productos_compuesto[$bproducto->codigo]['precio']*$this->productos_compuesto[$bproducto->codigo]['cantidad'];
        }

        $this->verificar_productos();
    }

    public function reiniciar_productos_compuesto()
     {
        $this->reset('productos_compuesto');
        $this->productos_compuesto_total = 0;
    }

    public function eliminar_item_producto_compuesto($item_id)
    {
        unset($this->productos_compuesto[$item_id]);
    }

    public function verificar_productos()
    {
            $this->productos_compuesto_total = 0;
                foreach ($this->productos_compuesto as $key => $pcompuesto)
                {
                    $this->productos_compuesto[$key]['total'] = $this->productos_compuesto[$key]['precio']*$this->productos_compuesto[$key]['cantidad'];
                    $this->productos_compuesto_total = $this->productos_compuesto_total + $this->productos_compuesto[$key]['total'];
                }
    }

        public function rules()
    {
        return [
            'designacion' => 'required',
            'marca_id' => [
                'required',
                Rule::exists('marcas', 'id')
            ],
            'categoria_id' => [
                'required',
                Rule::exists('categorias', 'id')
            ],
            'unidad_id' => [
                'required',
                Rule::exists('unidads', 'id')
            ],
            'codigo' => [
                'required',
                'min:5',
            ],
            'precio' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'cantidad' => [
                'required',
                'numeric',
                'gt:0'
            ],
        ];
    }

    public function obtener_stock_producto($producto_id,$almacen_id)
    {
        $bproducto = Producto::find($producto_id);

        $consulta_almacen_producto = ProductoAlmacen::where('producto_id',$producto_id)->where('almacen_id',$almacen_id)->first();
        if ($bproducto->tipo == 'estandar') {
            if ($consulta_almacen_producto) {
                return $consulta_almacen_producto->stock;
            }
            else {
                return 0;
            }
        }
        if ($bproducto->tipo == 'compuesto') {
            $cantidades = [];
            foreach ($bproducto->pcompuestos as $key => $pcom)
            {
                $con_alm_pro = ProductoAlmacen::where('producto_id',$pcom->producto_asignado_id)->where('almacen_id',$almacen_id)->first();
                if ($con_alm_pro) {$cantidades[] = $con_alm_pro->stock;}
            }

            if (count($cantidades) == 0) {
                return 0;
            }
            elseif(count($cantidades) > 0) {
                return min($cantidades);
            }
        }
    }

    public function actualizar_stock_producto($producto_id,$almacen_id,$signo,$cantidad)
    {
        $bproducto = Producto::find($producto_id);
        if ($bproducto->tipo == 'estandar')
        {
            $producto_almacen = ProductoAlmacen::where('producto_id',$producto_id)->where('almacen_id',$almacen_id)->first();
            if ($producto_almacen)
            {
                if ($signo == '+') {
                    $producto_almacen->stock = $producto_almacen->stock+$cantidad;
                }
                if ($signo == '-') {
                    $producto_almacen->stock = $producto_almacen->stock-$cantidad;
                }
                $producto_almacen->save();
            }
        }
        elseif ($bproducto->tipo == 'compuesto')
        {
            foreach ($bproducto->pcompuestos as $key => $pcom) {
                $producto_almacen = ProductoAlmacen::where('producto_id',$pcom->producto_asignado_id)->where('almacen_id',$almacen_id)->first();


                if ($producto_almacen)
                {
                    if ($signo == '+') {
                        $producto_almacen->stock = $producto_almacen->stock+($pcom->cantidad*$cantidad);
                    }
                    if ($signo == '-') {
                        $producto_almacen->stock = $producto_almacen->stock-($pcom->cantidad*$cantidad);
                    }
                    $producto_almacen->save();
                }
            }
        }
    }
}
