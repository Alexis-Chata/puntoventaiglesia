<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;
    protected $fillable = ['fecha','almacen_id','tgasto_id','monto','detalles','ignorar'];

    public function tgasto()
    {
        return $this->belongsTo(Tgasto::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function m_caja(){
        return $this->morphOne('App\Models\MCaja', 'm_cajable');
    }
}
