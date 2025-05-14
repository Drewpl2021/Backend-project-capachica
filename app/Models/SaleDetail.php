<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    // Definir la tabla si el nombre no sigue la convención
    protected $table = 'sale_details';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['id'];

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
