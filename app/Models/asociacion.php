<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asociacion extends Model
{
    use HasFactory;

    // Relación: Una asociación pertenece a una municipalidad
    public function municipalidad()
    {
        return $this->belongsTo(Municipalidad::class);
    }

    // Relación: Una asociación tiene muchos emprendedores
    public function emprendedores()
    {
        return $this->hasMany(Emprendedor::class);
    }

    // Relación: Una asociación tiene muchas imágenes asociadas
    public function imgAsociacions()
    {
        return $this->hasMany(Img_Asociacion::class);
    }
}
