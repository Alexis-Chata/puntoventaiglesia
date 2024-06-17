<?php

namespace App\Livewire\Forms;

use App\Models\Almacen;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class Almacen2Form extends Form
{
    public ?Almacen $almacen;

    #[Validate('required')]
    public $nombre;
    public $telefono;
    public $pais;
    public $ciudad;
    public $email;
    public $codigo_postal;

    public function set(Almacen $almacen){
        $this->almacen = $almacen;
        $this->nombre = $almacen->nombre;
        $this->telefono = $almacen->telefono;
        $this->pais = $almacen->pais;
        $this->ciudad = $almacen->ciudad;
        $this->email = $almacen->email;
        $this->codigo_postal = $almacen->codigo_postal;
    }

    public function update(){
        $this->almacen->update($this->all());
        //$this->almacen->save();
    }

    public function store()
    {
        $this->validate();
        if (isset($this->almacen)) {
            $this->update();
        } else {
            Almacen::create($this->all());
        }
    }

    public function agregar_descontar_monto_almacen($almacen_id,$monto,$accion)
    {
        $almacen = Almacen::find($almacen_id);
        if ($accion == '+') {$almacen->monto = $almacen->monto + $monto;}
        elseif($accion == '-'){$almacen->monto = $almacen->monto - $monto;}
        $almacen->save();
        return $almacen->monto;
    }

}
