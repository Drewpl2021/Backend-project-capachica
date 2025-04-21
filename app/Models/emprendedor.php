<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class emprendedor extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();  // Generar un UUID cuando se crea el modelo
        });
    }
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
