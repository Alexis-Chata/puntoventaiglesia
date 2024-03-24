<?php

namespace App\Livewire;

use App\Livewire\Forms\GastosForm;
use App\Models\Gasto;
use Livewire\Component;
use Livewire\WithPagination;

class GestionarGastos extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public GastosForm $gastoform;
    public $search = '';
    public $titlemodal = 'Añadir';
    public $pagina = 5;

    public function mount(){   }

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
        $gasto->delete();
        $this->updatedSearch();
    }

    public function render()
    {
        $gastos = Gasto::where('name','like','%'.$this->search.'%')->paginate($this->pagina); //metodo
        return view('livewire.gestionar-gastos', compact('gastos'));
    }
}
