<?php

namespace App\Livewire;

use App\Livewire\Forms\Almacen2Form;
use App\Livewire\Forms\AlmacenForm;
use App\Models\Almacen;
use App\Models\Configuracion;
use Livewire\Component;
use Livewire\WithPagination;

class GestionarAlmacen extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public Almacen2Form $almacenForm;
    public $configuracion;
    public $titlemodal = 'Añadir Almacen';
    public $search = '';
    public $pagina = 5;

    public function mount(){
        $this->configuracion = Configuracion::find(1);
    }
    public function updatedSearch(){
        $this->resetPage();
    }

    public function modal(Almacen $almacen = null)
    {
        $this->reset('titlemodal');
        $this->almacenForm->reset();
        if ($almacen->id == true) {
            $this->titlemodal = 'Editar Almacen';
            $this->almacenForm->set($almacen);
        }
    }

    public function guardar()
    {
        $this->almacenForm->store();
        $this->almacenForm->reset();
        $this->dispatch('cerrar_modal_almacen');
    }

    public function eliminar(Almacen $almacen){
        $almacen->delete();
        $this->updatedSearch();
    }

    public function render()
    {
        $almacens = Almacen::where('nombre','like','%'.$this->search.'%')->paginate($this->pagina);
        return view('livewire.gestionar-almacen', compact('almacens'));
    }
}
