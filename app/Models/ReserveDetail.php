<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $table = 'reserve_detail';

    // Definir los campos que son asignables en masa
    protected $fillable = [
        'emprendedor_service_id',
        'reserva_id',
        'description',
        'costo',
        'IGV',
        'BI',
        'total',
        'lugar',
    ];

    // Relación muchos a uno con la tabla emprendedor_service
    public function emprendimientoService()
    {
        return $this->belongsTo(EmprendedorService::class, 'emprendedor_service_id');
    }

    // Relación muchos a uno con la tabla reservas
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }
}
