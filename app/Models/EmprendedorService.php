<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmprendedorService extends Model
{
    use HasFactory;

    // 👇 porque tu PK es uuid y no autoincremental
    public $incrementing = false;
    protected $table = 'emprendedor_service'; // Especifica el nombre correcto

    protected $keyType = 'string';
    protected $guarded = ['id'];

    /**
     * Los atributos asignables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'service_id',
        'emprendedor_id',
        'code',
        'cantidad',
        'name',
        'description'
    ];

    /**
     * Evento para generar UUID automáticamente.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    /**
     * Relación: pertenece a un emprendedor.
     */
    public function emprendedor()
    {
        return $this->belongsTo(emprendedor::class, 'emprendedor_id', 'id');
    }

    /**
     * Relación: pertenece a un servicio.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
