<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Slider_Muni extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    // ✅ Campos que pueden ser asignados masivamente
    protected $fillable = [
        'municipalidad_id',
        'titulo',
        'descripcion',
        'url_images'
    ];

    // ✅ Relación correcta con Municipalidad
    public function municipalidad()
    {
        return $this->belongsTo(Municipalidad::class, 'municipalidad_id');
    }
}