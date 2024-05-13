<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    use HasFactory;

    public function devoluciondetalles(){
        return $this->hasMany(DevolucionDetalle::class,'devolucion_id');
    }

    public function posventa(){
        return $this->belongsTo(Posventa::class);
    }

    public function almacen(){
        return $this->belongsTo(Almacen::class);
    }

    public function movimientoable(){
        return $this->morphTo();
    }
}
