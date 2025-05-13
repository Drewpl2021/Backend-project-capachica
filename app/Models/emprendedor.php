<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class emprendedor extends Model
{
    use HasFactory;
    protected $table = 'users';
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'emprendedor_service',    // tabla pivote
            'emprendedor_id',         // foreign key de company en la pivote
            'service_id'          // foreign key de service en la pivote
        )->withPivot(['code', 'cantidad', 'name', 'description'])
            ->withTimestamps();
    }

}
