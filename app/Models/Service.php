<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $incrementing = false;
    use SoftDeletes;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'code',
        'costo',
        'category',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }


    // Relación muchos a muchos con Emprendedor a través del pivote
    public function emprendedores()
    {
        return $this->belongsToMany(Emprendedor::class, 'emprendedor_service', 'service_id', 'emprendedor_id')
            ->withPivot(['id', 'code', 'cantidad', 'name', 'description'])
            ->withTimestamps();
    }

    // Si quieres usar la tabla pivote como modelo (opcional)
    public function emprendedorServices()
    {
        return $this->hasMany(EmprendedorService::class, 'service_id');
    }

    public function imgservices()
    {
        return $this->hasMany(ImgService::class, 'service_id');
    }
}
