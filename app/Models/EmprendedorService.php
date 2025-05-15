<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmprendedorService extends Model
{
    use HasFactory;

    protected $table = 'emprendedor_service';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['id'];


    protected $fillable = [
        'service_id',
        'emprendedor_id',
        'code',
        'status',
        'cantidad',
        'costo',
        'costo_unidad',
        'name',
        'description',
    ];

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
        return $this->belongsTo(Emprendedor::class, 'emprendedor_id');
    }

    /**
     * Relación: pertenece a un servicio.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Relación a una reserva.
     */
    public function reserveDetails()
    {
        return $this->hasMany(ReserveDetail::class, 'emprendedor_service_id');
    }

    /**
     * Relación a una venta detallada.
     */
    public function saleDetail()
    {
        return $this->hasOne(SaleDetail::class, 'emprendedor_service_id');
    }
}
