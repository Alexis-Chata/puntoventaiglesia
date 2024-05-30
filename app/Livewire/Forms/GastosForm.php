<?php

namespace App\Livewire\Forms;

use App\Models\Almacen;
use App\Models\Gasto;
use App\Models\Movimiento;
use App\Models\Tgasto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class GastosForm extends Form
{
    public ?Gasto $gasto;
    private AlmacenForm $almacenform;
    private MovimientoForm $movimientoform;
    public $fecha;
    public $almacen_id;
    public $tgasto_id;
    public $monto;
    public $detalles;
    public $ignorar;



    public $rules = [
        'fecha' => 'required',
        'almacen_id' => 'required',
        'tgasto_id' => 'required',
        'monto' => 'required',
        ];

    public function set(Gasto $gasto)
    {
        $this->gasto = $gasto;
        $this->fecha = $gasto->fecha;
        $this->almacen_id = $gasto->almacen_id;
        $this->tgasto_id = $gasto->tgasto_id;
        $this->monto = $gasto->monto;
        $this->detalles = $gasto->detalles;
    }

    public function update(){
        $this->validate();
        $this->almacenform = new AlmacenForm();
        $this->movimientoform = new MovimientoForm();
        $bgasto = Gasto::find($this->gasto->id);
        ##########################
        if ($bgasto->ignorar == 0)
        {
            $saldo = $this->almacenform->agregar_descontar_monto_almacen($this->almacen_id,$bgasto->monto,'+');
            $this->movimientoform->agregar_movimiento($this->gasto->id,$this->almacen_id,$bgasto->monto,$saldo,'+','App\Models\Gasto','editar');
        }
            $btgasto = Tgasto::find($this->tgasto_id);
            $this->ignorar = $btgasto->ignorar;
            $this->gasto->update($this->all());

        ##########################
        if ($this->gasto->ignorar == 0)
        {
            $saldoa = $this->almacenform->agregar_descontar_monto_almacen($this->almacen_id,$this->monto,'-');
            $this->movimientoform->agregar_movimiento($this->gasto->id,$this->almacen_id,$this->monto,$saldoa,'-','App\Models\Gasto','editar');
        }
    }

    public function store()
    {
        $this->validate();
        $btgasto = Tgasto::find($this->tgasto_id);
        $this->ignorar = $btgasto->ignorar;
        $this->gasto = Gasto::create($this->all());
        #agregar monto almacen
        if ($this->ignorar == 0)
        {
            $this->almacenform = new AlmacenForm();
            $this->movimientoform = new MovimientoForm();
            $saldo = $this->almacenform->agregar_descontar_monto_almacen($this->almacen_id,$this->monto,'-');
            $this->movimientoform->agregar_movimiento($this->gasto->id,$this->almacen_id,$this->monto,$saldo,'-','App\Models\Gasto','crear');
        }
    }

    public function eliminar_gasto(Gasto $gasto){
        $this->almacenform = new AlmacenForm();
        $this->movimientoform = new MovimientoForm();
        $saldo = $this->almacenform->agregar_descontar_monto_almacen($gasto->almacen_id,$gasto->monto,'+');
        $this->movimientoform->agregar_movimiento($gasto->id,$gasto->almacen_id,$gasto->monto,$saldo,'+','App\Models\Gasto','eliminar');
        $gasto->delete();
    }
}
