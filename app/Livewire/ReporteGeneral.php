<?php

namespace App\Livewire;

use App\Livewire\Forms\ComprasForm;
use App\Livewire\Forms\PosVentaForm;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Configuracion;
use App\Models\Gasto;
use App\Models\PagoCompra;
use App\Models\Posventa;
use App\Models\ProductoAlmacen;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReporteGeneral extends Component
{
    public $salmacen;
    public PosVentaForm $posventaform;
    public ComprasForm $comprasform;
    public $search;
    public $pagina = 5;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nombre_tabla,$datos_servicios;

    public function updated(){

    }

    public function render()
    {

        $monto_ventas = Posventa::query();
        $monto_compras = PagoCompra::query();
        $monto_gastos = Gasto::query()->where('ignorar','0');

        $productos_almacen = ProductoAlmacen::query()->whereExists(function ($query)  {
            $query->select()
                  ->from(DB::raw('productos'))
                  ->whereColumn('producto_almacens.producto_id', 'productos.id')
                  ->where('productos.designacion','like','%'.$this->search.'%');
        })->where('stock','==',0);

        $monto_ventas->when($this->salmacen <> '',function ($q) {
            return $q->where('almacen_id',$this->salmacen);
        });

        $monto_compras->when($this->salmacen <> '',function ($q) {
            return $q->whereExists(function ($query)  {
                $query->select()
                      ->from(DB::raw('compras'))
                      ->whereColumn('pago_compras.compra_id', 'compras.id')
                      ->where('compras.almacen_id',$this->salmacen);
            });
        });

        $monto_gastos->when($this->salmacen <> '',function ($q) {
            return $q->where('almacen_id',$this->salmacen);
        });

        $productos_almacen->when($this->salmacen <> '',function ($q) {
            return $q->where('almacen_id',$this->salmacen);
        });

        $date = new DateTime('now');
        $date->modify('last day of this month');
        $productos_vendidos = $this->posventaform->obtener_productos_mas_vendidos(date('Y-m-1 00:00:00'), $date->format('Y-m-d')." 23:59:59");
        $monto_ventas =  $monto_ventas->where('created_at','>=',date('Y-m-d 00:00:00'))->where('created_at','<=',date('Y-m-d 23:59:59'))->sum('monto_pago');
        $monto_compras = $monto_compras->where('created_at','>=',date('Y-m-d 00:00:00'))->where('created_at','<=',date('Y-m-d 23:59:59'))->sum('monto_pago');
        $monto_gastos = $monto_gastos->where('created_at','>=',date('Y-m-d 00:00:00'))->where('created_at','<=',date('Y-m-d 23:59:59'))->sum('monto');
        $productos_almacen = $productos_almacen->paginate($this->pagina);

        $monto_deuda = Cliente::whereNotNull('deuda_total')->where('deuda_total','>',0)->sum('deuda_total');

        $almacens = Almacen::all();
        $configuracion = Configuracion::find(1);
        #graficos
        $puntos = [];
        $nombre_tab ='Reporte de Ventas';
        #fecha inicio
        $date = strtotime(date("Y-m-d"));
        $first =date('w') <> '1' ? date('Y-m-d',strtotime('last Monday')) :date('Y-m-d');
        $last = date('w') <> '0' ? date('Y-m-d',strtotime('next Sunday')) : date('Y-m-d');
        $nombre_tabla = 'Reporte de Ventas y Compras del :'.$first.' hasta '.$last;
        $ventas_semana = $this->posventaform->obtener_productos_vendidos_dias($first,$last,$this->salmacen);
        $compras_semana = $this->comprasform->obtener_productos_compras_dias($first,$last,$this->salmacen);
        $general = [];
        $general[0] =  $nombre_tabla;
        $general[1] =  $ventas_semana;
        $general[2] =  $compras_semana;
        $general[3] =  "Productos que generaron mas Ingresos ".date('Y');
        $date2 = new DateTime('now');
        $date2->modify('last day of this month');
        $general[4] =  $this->posventaform->obtener_productos_mas_vendidos_graficos(date('Y-01-1 00:00:00'), $date2->format('Y-m-d')." 23:59:59",5);
        $this->dispatch('ejecutar_consulta_barra',$general);
        return view('livewire.reporte-general',compact('almacens','configuracion','monto_ventas','monto_compras','monto_gastos','monto_deuda','productos_almacen','productos_vendidos'));
    }

}
