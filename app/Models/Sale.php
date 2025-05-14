<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Definir la tabla si el nombre no sigue la convención
    protected $table = 'sales';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['id'];


    // Definir los campos que son asignables en masa
    protected $fillable = [
        'emprendimiento_id',
        'payment_id',
        'reserva_id',
        'code',
        'IGV',
        'BI',
        'total',
    ];

    // Relación muchos a uno con la tabla emprendimiento
    public function emprendimiento()
    {
        return $this->belongsTo(Emprendimiento::class, 'emprendimiento_id');
    }

    // Relación muchos a uno con la tabla payment
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    // Relación muchos a uno con la tabla reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }
}
