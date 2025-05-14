<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Emprendedor extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $incrementing = false;

    protected $fillable = [
        'razon_social',
        'address',
        'user_id',
        'code',
        'ruc',
        'description',
        'lugar',
        'img_logo',
        'name_family',
        'asociacion_id',
    ];

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

    // Relación muchos a muchos con User
    public function users()
    {
        return $this->belongsToMany(User::class, 'emprendedor_user', 'emprendedor_id', 'user_id');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'emprendedor_service', 'emprendedor_id', 'service_id');
    }
}
