<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $fillable =
    [
        'moneda_id',
        'email_predeterminado',
        'logo',
        'name',
        'telefono_empresa',
        'desarrollador',
        'pie_pagina',
        'direccion',
        'farmacia',
        'pagina_factura',
        'pie_pagina_factura',
        'cotizacion_stock',
        'almacen_id',
        'descripcion',
        'descripcion2',
        'ancho_impresion',
    ];
    use HasFactory;

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }
}
