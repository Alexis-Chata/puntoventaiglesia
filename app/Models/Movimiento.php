<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    public function movimientoable()
    {
        return $this->morphTo();
    }
    #relaciones polimorficas
   /* public function posventa() {
        return $this->morphOne('App\Models\Posventa','movimientoable');
    }

    public function compra() {
        return $this->morphOne('App\Models\Compra','movimientoable');
    }



    public function devolucione() {
        return $this->morphOne('App\Models\Devolucion','movimientoable');
    }*/

}
