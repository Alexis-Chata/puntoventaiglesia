<?php

namespace App\Exports;

use App\Models\Configuracion;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;

class ReporteGeneralExcel implements FromView
{
    private $monto_ventas;
    private $monto_compras;
    private $monto_deuda;
    private $monto_devoluciones;
    private $monto_gastos;
    private $monto_com_by_vent;
    private $configuracion;
    private $lista_ventas;
    private $lista_compras;
    private $lista_devoluciones;
    private $lista_gastos;


    public function __construct
    (
        $monto_ventas,
        $monto_compras,
        $monto_deuda,
        $monto_devoluciones,
        $monto_gastos,
        $monto_com_by_vent,
        $configuracion,
        $lista_ventas,
        $lista_compras,
        $lista_devoluciones,
        $lista_gastos
        )
    {
        $this->monto_ventas = $monto_ventas;
        $this->monto_compras = $monto_compras;
        $this->monto_deuda = $monto_deuda;
        $this->monto_devoluciones = $monto_devoluciones;
        $this->monto_gastos = $monto_gastos;
        $this->monto_com_by_vent = $monto_com_by_vent;
        $this->configuracion = $configuracion;
        $this->lista_ventas = $lista_ventas;
        $this->lista_compras = $lista_compras;
        $this->lista_devoluciones = $lista_devoluciones;
        $this->lista_gastos = $lista_gastos;
    }

    use Exportable;
    public function view(): View
    {
        return view('administrador.reportes.reportes_general_excel', [
            'monto_ventas' => $this->monto_ventas,
            'monto_compras' => $this->monto_compras,
            'monto_deuda' => $this->monto_deuda,
            'monto_devoluciones' => $this->monto_devoluciones,
            'monto_gastos' => $this->monto_gastos,
            'monto_com_by_vent' => $this->monto_com_by_vent,
            'configuracion' => $this->configuracion,
            'lista_ventas' => $this->lista_ventas,
            'lista_compras' => $this->lista_compras,
            'lista_devoluciones' => $this->lista_devoluciones,
            'lista_gastos' => $this->lista_gastos,
        ]);
    }
}
