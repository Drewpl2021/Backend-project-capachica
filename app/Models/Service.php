<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();  // Generar un UUID cuando se crea el modelo
        });
    }
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'codigo',
    ];

    /**
     * Relación: un servicio pertenece a muchas compañías a través de la tabla pivote company_service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // Relación muchos a muchos con Emprendedor
    public function emprendedores()
    {
        return $this->belongsToMany(Emprendedor::class, 'emprendedor_user', 'service_id', 'emprendedor_id');
    }
}
