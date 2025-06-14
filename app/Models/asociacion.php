<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Asociacion extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nombre', 'descripcion', 'lugar', 'estado', 'municipalidad_id', 'url'];

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
        return $this->hasMany(Img_asociacion::class, 'asociacion_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();  // Generar un UUID cuando se crea el modelo
        });
    }
}
