<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Slider_Muni extends Model
{
    use HasFactory;
    public $incrementing = false;
    use SoftDeletes;

    protected $keyType = 'string';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
    // Campos que pueden ser asignados masivamente
    protected $fillable = ['municipalidad_id', 'titulo', 'descripcion'];


    // Relación pertenece a una Municipalidad
    public function Municipalidad()
    {
        return $this->belongsTo(Municipalidad::class, 'municipalidad_id');
    }
}
