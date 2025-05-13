<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class img_asociacion extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    // Relación: Una imagen de asociación pertenece a una asociación
    protected $fillable = [
        'asociacion_id',
        'url_image',
        'estado',
        'codigo'
    ];
    public function asociacion()
    {
        return $this->belongsTo(Asociacion::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
