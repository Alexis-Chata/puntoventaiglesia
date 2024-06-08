<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AjustesSistemaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $n_configuracion = new Configuracion();
        $n_configuracion->moneda_id = 1;
        $n_configuracion->email_predeterminado = 'ing.anthonny.joel@gmail.com';
        $n_configuracion->logo = 'imagenes/logo.png';
        $n_configuracion->name = 'Ferretería Gemelos';
        $n_configuracion->telefono_empresa = '987987987';
        $n_configuracion->desarrollador = 'JNetwork';
        $n_configuracion->pie_pagina = 'Esto es una prueba';
        $n_configuracion->direccion = 'Mz C Barranco';
        $n_configuracion->descripcion = 'descripcion';
        $n_configuracion->descripcion2 = 'descripcion2';
        $n_configuracion->pagina_factura = null;
        $n_configuracion->pie_pagina_factura = 'esto es una prueba';
        $n_configuracion->cotizacion_stock = null;
        $n_configuracion->almacen_id = 1;
        $n_configuracion->save();
    }
}
