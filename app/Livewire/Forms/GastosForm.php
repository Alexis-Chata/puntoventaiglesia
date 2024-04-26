<?php

namespace App\Livewire\Forms;

use App\Models\Gasto;
use App\Models\Tgasto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class GastosForm extends Form
{
    public ?Gasto $gasto;
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
        $this->almacen_id = $gasto->almacen_id;
        $this->tgasto_id = $gasto->tgasto_id;
        $this->monto = $gasto->monto;
        $this->detalles = $gasto->detalles;
    }

    public function update(){
        $this->validate();
        $btgasto = Tgasto::find($this->tgasto_id);
        $this->ignorar = $btgasto->ignorar;
        $this->gasto->update($this->all());
    }

    public function store()
    {
        $this->validate();
        $btgasto = Tgasto::find($this->tgasto_id);
        $this->ignorar = $btgasto->ignorar;
        $this->gasto = Gasto::create($this->all());
    }
}
