<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class emprendedor extends Model
{
    use HasFactory;
    // Relación: Un emprendedor pertenece a una asociación
    public function asociacion()
    {
        return $this->belongsTo(Asociacion::class);
    }

    public function destinosTurismo()
    {
        return $this->hasMany(DestinosTuriscos::class);
    }
}
