<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinosTuriscos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'lugar',
        'emprendedor_id'
    ];

    public function emprendedor()
    {
        return $this->belongsTo(Emprendedor::class);
    }
}
