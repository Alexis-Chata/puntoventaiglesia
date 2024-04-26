<?php

namespace App\Livewire\Forms;

use App\Models\Tgasto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TgastoForm extends Form
{
    public ?Tgasto $tgasto;
    public $name;
    public $descripcion;
    public $ignorar;

    public $rules = [
        'name' => 'required',
        ];

    public function set(Tgasto $tgasto)
    {
        $this->tgasto = $tgasto;
        $this->name = $tgasto->name;
        $this->ignorar = $tgasto->ignorar;
        $this->descripcion = $tgasto->descripcion;
    }

    public function update(){
        $this->validate();
        $this->tgasto->update($this->all());
    }

    public function store()
    {
        $this->validate();
        Tgasto::create($this->all());
    }
}
