<?php

namespace App\Livewire\Forms;

use App\Models\Configuracion;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AjusteSistemaForm extends Form
{
    public ?Configuracion $configuracion;
    public $moneda_id;
    public $email_predeterminado;
    public $name;
    public $telefono_empresa;
    public $desarrollador;
    public $pie_pagina;
    public $direccion;
    public $descripcion;
    public $descripcion2;
    public $pagina_factura;
    public $pie_pagina_factura;
    public $cotizacion_stock;
    public $farmacia;
    public $ancho_impresion;
    public $almacen_id;

    public $rule_ajuste = [
        'moneda_id' => 'required',
        'email_predeterminado' => 'required',
        'almacen_id' => 'required',
        'name' => 'required',
    ];

    public function set(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
        $this->moneda_id = $configuracion->moneda_id;
        $this->email_predeterminado = $configuracion->email_predeterminado;
        $this->name = $configuracion->name;
        $this->telefono_empresa = $configuracion->telefono_empresa;
        $this->desarrollador = $configuracion->desarrollador;
        $this->pie_pagina = $configuracion->pie_pagina;
        $this->direccion = $configuracion->direccion;
        $this->farmacia = $configuracion->farmacia;
        $this->pagina_factura = $configuracion->pagina_factura;
        $this->pie_pagina_factura = $configuracion->pie_pagina_factura;
        $this->cotizacion_stock = $configuracion->cotizacion_stock;
        $this->almacen_id = $configuracion->almacen_id;
        $this->descripcion = $configuracion->descripcion;
        $this->descripcion2 = $configuracion->descripcion2;
        $this->ancho_impresion = $configuracion->ancho_impresion;
    }

    public function update($imagen_logo = null){
        $this->validate($this->rule_ajuste);
        $this->configuracion->update($this->all());

        if ($imagen_logo)
        {
            $this->eliminar_imagen();
            $this->subir_imagen($imagen_logo);
        }
    }

    public function store($imagen_logo = null)
    {
        $this->validate($this->rule_ajuste);
        $this->configuracion = Configuracion::create($this->all());
        if ($imagen_logo) {
           $this->subir_imagen($imagen_logo);
        }
    }

    public function subir_imagen($imagen)
    {
        $extension = $imagen->extension();
        $img_empresa = $imagen->storeAs('public/logo-empresa', $this->configuracion->id.strtotime(date('Y-m-d h:i:s')).".".$extension);
        $this->configuracion->logo = Storage::url($img_empresa);
        $this->configuracion->save();
    }

    public function eliminar_imagen()
    {
        if ($this->configuracion->logo == true)
        {
            $eliminar = str_replace('storage', 'public', $this->configuracion->logo);
            Storage::delete([$eliminar]);
        }
    }

}
