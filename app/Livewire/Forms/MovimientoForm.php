<?php

namespace App\Livewire\Forms;

use App\Models\Almacen;
use App\Models\Movimiento;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MovimientoForm extends Form
{
    public function __construct()
    {

    }

    public function eliminar_movimiento($id_movimiento,$modelo)
    {
        $bmovimiento = Movimiento::where('movimientoable_id',$id_movimiento)->where('movimientoable_type',$modelo)->first();
        $bmovimiento->delete();
    }

    public function agregar_movimiento($gasto_id,$almacen_id,$monto,$saldo,$accion,$modelo,$tipo_accion)
    {
        #agregar movimiento
        $balmacen = Almacen::find($almacen_id);
        $n_mov = new Movimiento();
        $n_mov->tipo_movimiento = $accion;
        $n_mov->monto = $monto;
        $n_mov->saldo =  $saldo;
        $n_mov->accion =  $tipo_accion;
        $n_mov->movimientoable_type =  $modelo;
        $n_mov->movimientoable_id =  $gasto_id;
        $n_mov->almacen = $balmacen->id;
        $n_mov->save();
    }
}
