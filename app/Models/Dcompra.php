<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dcompra extends Model
{
    use HasFactory;

    public function dcompracompuestos()
    {
        return $this->hasMany(Dcompracompuesto::class);
    }

}
