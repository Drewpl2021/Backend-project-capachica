<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Municipalidad extends Model
{

    use HasFactory;
    use SoftDeletes;
    // Relación uno a uno con la descripción
    // Si el nombre de la tabla no sigue la convención plural, especifica el nombre de la tabla aquí
    // protected $table = 'municipalidads'; // (si necesario)
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'municipalidads';
    // Permite la asignación masiva de estos campos
    protected $fillable = [
        'distrito',
        'provincia',
        'region',
        'codigo',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
    // Relación uno a uno con municipio_descrip
    public function descripcion()
    {
        return $this->hasOne(Municipalidad_Descripcion::class, 'municipalidad_id');
    }

    // Relación: Una municipalidad tiene muchas asociaciones
    public function asociaciones()
    {
        return $this->hasMany(Asociacion::class);
    }

    // Relación: Una municipalidad tiene muchas imagenes
    public function sildermuni()
    {
        return $this->hasMany(Slider_Muni::class);
    }

    // Relación uno a muchos con Slider_Muni
    public function slider_munis()
    {
        return $this->hasMany(Slider_Muni::class, 'municipalidad_id');
    }
}
