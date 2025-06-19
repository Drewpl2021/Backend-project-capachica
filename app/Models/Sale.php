<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sale extends Model
{
    use HasFactory;
    use HasRelationships;

    // Definir la tabla si el nombre no sigue la convención
    protected $table = 'sales';
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
        'emprendedor_id',
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
        return $this->belongsTo(Emprendedor::class, 'emprendedor_id');
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

    public function user()
    {
        return $this->belongsToDeep(User::class, [Reserva::class]);
    }
}
