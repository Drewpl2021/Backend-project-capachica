<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Slider_Muni extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
    // Campos que pueden ser asignados masivamente
    protected $fillable = ['municipio_descrip_id', 'titulo', 'descripcion'];
    public function municipioDescrip()
    {
        return $this->belongsTo(Municipalidad_Descripcion::class, 'municipio_descrip_id');
    }

    public function images()
    {
        return $this->hasMany(Imagen_Slider::class, 'slider_id');
    }
}
