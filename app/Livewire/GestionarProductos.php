<?php

namespace App\Livewire;

use App\Exports\ReporteProductosExport;
use App\Livewire\Forms\ProductoForm;
use App\Models\Categoria;
use App\Models\Configuracion;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Unidad;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class GestionarProductos extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public ProductoForm $productoForm;

    public $titlemodal = 'Añadir Producto';
    public $search = '';
    public $pagina = 5;
    public $imagen_producto;
    public $iteration = 1;
    public $stproducto;
    public $bproducto;
    public $buscar_marca = '';
    public $buscar_categoria = '';
    public $buscar_marca_oculto = '';
    public $buscar_categoria_oculto = '';
    public $configuracion;
    public function mount(){
        $this->configuracion = Configuracion::find(1);
    }

    public function updatedBuscarMarca()
    {
        $this->iteration++;
        $this->dispatch('activar_buscador_marca');

        $bmarca = Marca::where('name',$this->buscar_marca)->first();

        if($bmarca == false){
            $this->buscar_marca_oculto = null;
        }
    }

    public function updatedBuscarCategoria()
    {
        $this->iteration++;
        $this->dispatch('activar_buscador_categoria');

        $bcategoria = Categoria::where('name',$this->buscar_categoria)->first();

        if($bcategoria == false){
            $this->buscar_categoria_oculto = null;
        }
    }

    public function updatedProductoform(){
        if($this->productoForm->tipo != 'compuesto'){
            $this->productoForm->reiniciar_productos_compuesto();
        }
        $this->productoForm->verificar_productos();
    }

    public function updatedBproducto(){
        $this->dispatch('activar_buscador_producto');
    }

    public function agregar_producto_compuesto()
    {
        $this->productoForm->agregar_producto_compuesto($this->bproducto);
        $this->reset('bproducto');
    }

    public function eliminar_producto_compuesto($item_id){
        $this->productoForm->eliminar_item_producto_compuesto($item_id);
        $this->productoForm->verificar_productos();
    }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function modal(Producto $producto = null)
    {
        $this->reset('titlemodal', 'imagen_producto','bproducto','buscar_marca_oculto','buscar_marca','buscar_categoria_oculto','buscar_categoria');
        $this->iteration++;
        $this->productoForm->reset();
        $this->productoForm->resetValidation();
        $this->productoForm->simbologia = 'C128';
        if ($producto->id == true) {
            $this->titlemodal = 'Editar Producto';
            $this->productoForm->set($producto);
            $this->buscar_marca_oculto = $this->productoForm->producto->marca_id;
            $this->buscar_marca = Marca::find($this->productoForm->producto->marca_id) ? Marca::find($this->productoForm->producto->marca_id)->name : null;
            $this->buscar_categoria_oculto = $this->productoForm->producto->categoria_id;
            $this->buscar_categoria = Categoria::find($this->productoForm->producto->categoria_id)->name;
        }
    }

    public function guardar()
    {
        $bmarca = Marca::find($this->buscar_marca_oculto);
        $this->productoForm->marca_id =  $bmarca ? $this->buscar_marca_oculto : null;
        $bcategoria = Categoria::find($this->buscar_categoria_oculto);
        $this->productoForm->categoria_id =  $bcategoria ? $this->buscar_categoria_oculto : null;
        $this->productoForm->store($this->imagen_producto);
        $this->productoForm->reset();
        $this->productoForm->resetValidation();
        $this->dispatch('cerrar_modal_producto');
    }

    public function eliminar(Producto $producto){
        $producto->delete();
        $this->updatedSearch();
    }

    public function generar_codigo(){
        $bcodigo = true;
        while ($bcodigo == true) {
            $codigo = rand(10000000,99999999);
            $bcodigo = Producto::where('codigo',$codigo)->first();
        }
        $this->productoForm->codigo = $codigo;
    }

    public function descargar_reporte_productos_excel()
    {
        $lista_productos = Producto::query()->Where(function($query) {$query->orwhere('designacion','like','%'.$this->search.'%')->orwhere('codigo','like','%'.$this->search.'%');;});
        $lista_productos->when($this->stproducto <> '',function ($q) {return $q->where('tipo',$this->stproducto);});
        $lista_productos = $lista_productos->get();
        return $this->productoForm->descargar_reporte_productos_excel($lista_productos);
    }

    public function descargar_reporte_productos_pdf(){
        $lista_productos = Producto::query()->Where(function($query) {
            $query->orwhere('designacion','like','%'.$this->search.'%')->orwhere('codigo','like','%'.$this->search.'%');;
        });

        $lista_productos->when($this->stproducto <> '',function ($q) {
            return $q->where('tipo',$this->stproducto);
        });

        $lista_productos = $lista_productos->get();
        return $this->productoForm->descargar_reporte_productos_pdf($lista_productos);
    }

    public function render()
    {

        $categorias = Categoria::all();
        $marcas = Marca::all();
        $unidades = Unidad::all();
        $lista_productos = Producto::query()->Where(function($query) {$query->orwhere('designacion','like','%'.$this->search.'%')->orwhere('codigo','like','%'.$this->search.'%');});
        $lista_productos->when($this->stproducto <> '',function ($q) {return $q->where('tipo',$this->stproducto);});
        $lista_productos = $lista_productos->paginate($this->pagina);

        return view('livewire.gestionar-productos', compact('lista_productos', 'categorias', 'marcas', 'unidades'));
    }
}
