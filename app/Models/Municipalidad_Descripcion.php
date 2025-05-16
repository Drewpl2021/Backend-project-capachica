<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Municipalidad_Descripcion extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['municipalidad_id', 'logo', 'direccion', 'descripcion', 'ruc', 'correo', 'nombre_alcalde', 'anio_gestion'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
    // Relación inversa: cada descripción pertenece a una municipalidad
    public function municipalidad()
    {
        return $this->belongsTo(Municipalidad::class, 'municipalidad_id');
    }
    public function sliders()
    {
        return $this->hasMany(Slider_Muni::class, 'municipio_descrip_id');
    }
}
