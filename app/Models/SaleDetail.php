<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SaleDetail extends Model
{
    use HasFactory;

    // Definir la tabla si el nombre no sigue la convención
    protected $table = 'sale_details';
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['id'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    // Definir los campos que son asignables en masa
    protected $fillable = [
        'emprendedor_service_id',
        'sale_id',
        'description',
        'costo',
        'IGV',
        'BI',
        'total',
        'lugar',
    ];

    // Relación muchos a uno con Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    // Relación uno a uno con EmprendedorService
    public function emprendimientoService()
    {
        return $this->belongsTo(EmprendedorService::class, 'emprendedor_service_id');
    }
}
