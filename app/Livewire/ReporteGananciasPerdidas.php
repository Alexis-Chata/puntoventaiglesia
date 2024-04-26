<?php

namespace App\Livewire;

use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\Configuracion;
use App\Models\Devolucion;
use App\Models\Gasto;
use App\Models\PagoCompra;
use App\Models\Posventa;
use App\Models\PosventaDetalle;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReporteGananciasPerdidas extends Component
{
    public $salmacen,$fecha_inicial,$fecha_final;

    public function mount(){
        $this->fecha_inicial = date('Y-m').'-01';

        $date = new DateTime('now');
        $date->modify('last day of this month');
        $date->format('Y-m-d');
        $this->fecha_final = $date->format('Y-m-d');


    }
    public function render()
    {
        $monto_ventas = Posventa::query();
        $monto_compras = PagoCompra::query();
        $monto_devoluciones = Devolucion::query();
        $monto_gastos = Gasto::query();
        $monto_com_by_vent = PosventaDetalle::query()->whereExists(function ($query)  {
            $query->select()
                  ->from(DB::raw('posventas'))
                  ->whereColumn('posventa_detalles.posventa_id', 'posventas.id')
                  ->where('posventas.estado_posventa','<>','eliminado');
        });

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

        $monto_com_by_vent->when($this->salmacen <> '',function ($q) {
            return $q->whereExists(function ($query)  {
                $query->select()
                      ->from(DB::raw('posventas'))
                      ->whereColumn('posventa_detalles.posventa_id', 'posventas.id')
                      ->where('posventas.almacen_id',$this->salmacen);
            });
        });

        $monto_devoluciones->when($this->salmacen <> '',function ($q) {
            return $q->where('almacen_id',$this->salmacen);
        });

        $monto_gastos->when($this->salmacen <> '',function ($q) {
            return $q->where('almacen_id',$this->salmacen);
        });

        $monto_com_by_vent =  $monto_com_by_vent->where('created_at','>=',$this->fecha_inicial." 00:00:00")->where('created_at','<=',$this->fecha_final." 23:59:59")->sum('producto_costo_compra');
        $monto_ventas =  $monto_ventas->where('created_at','>=',$this->fecha_inicial." 00:00:00")->where('created_at','<=',$this->fecha_final." 23:59:59")->sum('monto_pago');
        $monto_compras = $monto_compras->where('created_at','>=',$this->fecha_inicial." 00:00:00")->where('created_at','<=',$this->fecha_final." 23:59:59")->sum('monto_pago');
        $monto_gastos = $monto_gastos->where('created_at','>=',$this->fecha_inicial." 00:00:00")->where('ignorar','0')->where('created_at','<=',$this->fecha_final." 23:59:59")->sum('monto');
        $monto_devoluciones = $monto_devoluciones->where('created_at','>=',$this->fecha_inicial." 00:00:00")->where('created_at','<=',$this->fecha_final." 23:59:59")->sum('monto_pago');
        $monto_deuda = Cliente::whereNotNull('deuda_total')->where('deuda_total','>',0)->sum('deuda_total');
        $almacens = Almacen::all();
        $configuracion = Configuracion::find(1);
        return view('livewire.reporte-ganancias-perdidas',compact('monto_com_by_vent','almacens','configuracion','monto_ventas','monto_compras','monto_gastos','monto_devoluciones','monto_deuda'));
    }
}
