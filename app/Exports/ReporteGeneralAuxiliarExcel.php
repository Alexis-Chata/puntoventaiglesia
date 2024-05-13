<?php

namespace App\Exports;

use App\Models\Configuracion;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;

class ReporteGeneralAuxiliarExcel implements FromView
{
    private $movimientos;
    private $finicio;
    private $ffinal;
    private $almacen;
    private $configuracion;

    public function __construct
    (
        $finicio,
        $ffinal,
        $movimientos,
        $almacen,
        )
    {
        $this->finicio = $finicio;
        $this->ffinal = $ffinal;
        $this->movimientos = $movimientos;
        $this->almacen = $almacen;
    }

    use Exportable;
    public function view(): View
    {
        $configuracion = Configuracion::find(1);
        return view('administrador.reportes.reportes_general_auxiliar_excel', [
            'finicio' => $this->finicio,
            'ffinal' => $this->ffinal,
            'movimientos' => $this->movimientos,
            'almacen' => $this->almacen,
            'configuracion' => $configuracion,
        ]);
    }
}

