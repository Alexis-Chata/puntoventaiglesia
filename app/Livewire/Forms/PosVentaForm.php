<?php

namespace App\Livewire\Forms;

use App\Exports\ReporteVentasExport;
use App\Models\Configuracion;
use App\Models\Posventa;
use App\Models\PosventaDetalle;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class PosVentaForm extends Form
{
    public ?Posventa $posventa;

    public function descargar_reporte_ventas_excel($posventas){
        return Excel::download(new ReporteVentasExport($posventas), 'ReporteVentas.xlsx');
    }

    public function descargar_reporte_ventas_pdf($posventas){
        $configuracion = Configuracion::find(1);
        $nombre_archivo = 'ReporteDeVentas-' . date("Y-m-d H:i:s") . '.pdf';
        $consultapdf = FacadePdf::loadView('administrador.ventas.reporte_ventas_pdf', compact('posventas', 'configuracion'))->setPaper('a4', 'landscape');
        $pdfContent = $consultapdf->output();
        return response()->streamDownload(
            fn () => print($pdfContent),
            $nombre_archivo
        );
    }

    public function obtener_productos_mas_vendidos($finicio,$ffinal){
        $lista_productos = [];
        $dventas = PosventaDetalle::select('producto_id','producto_nombre')->where('created_at','>=',$finicio)->where('created_at','<=',$ffinal)->distinct('producto_id')->get()->unique('producto_id');
        foreach ($dventas as $key => $dventa)
        {
            $monto_total = PosventaDetalle::where('producto_id',$dventa->producto_id)->where('created_at','>=',$finicio)->where('created_at','<=',$ffinal)->sum('producto_importe');
            $lista_productos[$key]['producto_id']=$dventa->producto_id;
            $lista_productos[$key]['monto'] = PosventaDetalle::where('producto_id',$dventa->producto_id)->where('created_at','>=',$finicio)->where('created_at','<=',$ffinal)->sum('producto_importe');
            $lista_productos[$key]['producto_nombre'] = $dventa->producto_nombre;
            $lista_productos[$key]['venta_totales'] = PosventaDetalle::where('producto_id',$dventa->producto_id)->where('created_at','>=',$finicio)->where('created_at','<=',$ffinal)->sum('producto_cantidad');
        }


        usort($lista_productos, function ($a, $b) { return $b['monto'] - $a['monto']; });
        return $lista_productos;
    }

    public function obtener_productos_mas_vendidos_graficos($finicio,$ffinal,$numero){
        $lista_productos = [];
        $dventas = PosventaDetalle::select('producto_id','producto_nombre')->where('created_at','>=',$finicio)->where('created_at','<=',$ffinal)->distinct('producto_id')->get()->unique('producto_id');
        foreach ($dventas as $key => $dventa)
        {
            $monto_total = PosventaDetalle::where('producto_id',$dventa->producto_id)->where('created_at','>=',$finicio)->where('created_at','<=',$ffinal)->sum('producto_importe');
            $lista_productos[$key] = ['name' => $dventa->producto_nombre,'y' => PosventaDetalle::where('producto_id',$dventa->producto_id)->where('created_at','>=',$finicio)->where('created_at','<=',$ffinal)->sum('producto_importe')];
        }

        usort($lista_productos, function ($a, $b) { return $b['y'] - $a['y']; });
        #eliminar array
        for ($i=0; $i <$dventas->count() ; $i++) {
            if ($i > ($numero-1)) {
                unset($lista_productos[$i]);
            }
        }
        return $lista_productos;
    }

    public function descargar_pdf(Posventa $posventa)
    {
            $paper_examen = 0;
            $paper_heigth = 460;
            $paper_heigth = $paper_examen + $paper_heigth;
            $items_adicional = 18.2;
            if ($posventa->descuento > 0) {
                $items_adicional = $items_adicional+2;
            }
            if ($posventa->envio > 0) {
                $items_adicional = $items_adicional+2;
            }
            if ($posventa->impuesto_monto > 0) {
                $items_adicional = $items_adicional+2;
            }

            $configuracion = Configuracion::find(1);
            $nombre_archivo = 'comprobante-' . date("Y-m-d H:i:s") . '.pdf';
            $consultapdf = FacadePdf::loadView('administrador.pdf.comprobante', compact('posventa', 'configuracion'))->setPaper([0, 0, 215.25, $paper_heigth + $items_adicional * 2 * ($posventa->posventadetalles->count())]);
            $pdfContent = $consultapdf->output();
            return response()->streamDownload(
                fn () => print($pdfContent),
                $nombre_archivo
            );
    }

    public function obtener_productos_vendidos_dias($fecha_inicia,$fecha_final,$almacen = null){
        $ventas = [];
        $fecha1= new DateTime($fecha_inicia);
        $fecha2= new DateTime($fecha_final);
        $diff = $fecha1->diff($fecha2);
        $dias = $diff->days;

        $dia_actual = $fecha_inicia;
        for ($i=0; $i <= $dias; $i++)
        {
            if ($almacen <> null) {
                $ventas[] =  Posventa::where('created_at','>=',date($dia_actual.' 00:00:00'))->where('created_at','<=',date($dia_actual.' 23:59:59'))->where('almacen_id',$almacen)->sum('monto_pago');
            }
            else {
                $ventas[] =  Posventa::where('created_at','>=',date($dia_actual.' 00:00:00'))->where('created_at','<=',date($dia_actual.' 23:59:59'))->sum('monto_pago');
            }
            $dia_actual = strtotime('+1 day', strtotime($dia_actual));
            $dia_actual = date('Y-m-d', $dia_actual);
        }
        return $ventas;
    }
}
