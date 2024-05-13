<?php

namespace App\Livewire;

use App\Livewire\Forms\GastosForm;
use App\Models\Almacen;
use App\Models\Configuracion;
use App\Models\Gasto;
use App\Models\Tgasto;
use Livewire\Component;
use Livewire\WithPagination;

class GestionarGastos extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $configuracion;
    public $search = '';
    public $pagina = 5;
    public GastosForm $gastoform;
    public $titlemodal = 'Añadir';

    public function mount(){  $this->configuracion = Configuracion::find(1); }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function modal(Gasto $gasto = null)
    {
        $this->reset('titlemodal');
        $this->gastoform->reset();
        if ($gasto->id == true) {
            $this->titlemodal = 'Editar';
            $this->gastoform->set($gasto);
        }
    }

    public function guardar()
    {
        if (isset($this->gastoform->gasto->id)) {$this->gastoform->update();}
        else {$this->gastoform->store();}
        $this->dispatch('cerrar_modal_gasto');
    }

    public function eliminar(Gasto $gasto){
        $this->gastoform->eliminar_gasto($gasto);
        $this->updatedSearch();
    }

    public function render()
    {
        $almacens = Almacen::all();
        $tgastos = Tgasto::all();
        $gastos = Gasto::where('detalles','like','%'.$this->search.'%')->paginate($this->pagina); //metodo
        return view('livewire.gestionar-gastos', compact('gastos','tgastos','almacens'));
    }
}
