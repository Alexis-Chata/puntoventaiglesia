<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable =
    [
        'fecha',
        'proveedor_id',
        'almacen_id',
    ];
    use HasFactory;

    public function movimientoable(){
        return $this->morphTo();
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function pagocompras()
    {
        return $this->hasMany(PagoCompra::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function dcompras(){
        return  $this->hasMany(Dcompra::class);
    }
}
