<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class img_asociacion extends Model
{
    use HasFactory;

    // Relación: Una imagen de asociación pertenece a una asociación
    public function asociacion()
    {
        return $this->belongsTo(Asociacion::class);
    }
}
