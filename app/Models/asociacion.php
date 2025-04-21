<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class asociacion extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['municipalidad_id', 'nombre', 'descripcion', 'lugar', 'estado'];

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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();  // Generar un UUID cuando se crea el modelo
        });
    }
}
