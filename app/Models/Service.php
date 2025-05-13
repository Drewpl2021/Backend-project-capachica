<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'codigo',
    ];

    /**
     * Relación: un servicio pertenece a muchas compañías a través de la tabla pivote company_service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function emprendedor()
    {
        return $this->belongsToMany(
            emprendedor::class,
            'emprendedor_service',
            'service_id',
            'emprendedor_id'
        )->withPivot(['code', 'cantidad', 'name', 'description'])
            ->withTimestamps();
    }
}
