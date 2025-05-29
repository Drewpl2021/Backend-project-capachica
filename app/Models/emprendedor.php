<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Emprendedor extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'emprendedors';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'razon_social',
        'address',
        'code',
        'ruc',
        'description',
        'lugar',
        'img_logo',
        'name_family',
        'status',
        'asociacion_id',
    ];
    //Generar el UUID FACIL PARA LA CREACION
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }


    // Relaciónes *****************************


    // Asociación con asociación (belongsTo)
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
        return $this->belongsToMany(Service::class, 'emprendedor_service', 'emprendedor_id', 'service_id')
            ->withPivot([
                'id',
                'code',
                'status',
                'cantidad',
                'costo',
                'costo_unidad',
                'name',
                'description',
            ])
            ->withTimestamps();
    }


    public function emprendedorServices()
    {
        return $this->hasMany(EmprendedorService::class, 'emprendedor_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'emprendimiento_id');
    }

    // Relación: Una asociación tiene muchas imágenes asociadas
    public function imgEmprendedores()
    {
        return $this->hasMany(ImgEmprendedor::class, 'emprendedor_id');
    }
}
