<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Municipalidad_Descripcion extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'municipalidad_id',
        'logo',
        'direccion',
        'descripcion',
        'ruc',
        'correo',
        'nombre_alcalde',
        'anio_gestion'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    // Relación inversa con Municipalidad
    public function municipalidad()
    {
        return $this->belongsTo(Municipalidad::class, 'municipalidad_id');
    }
}