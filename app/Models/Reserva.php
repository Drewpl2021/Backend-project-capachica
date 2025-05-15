<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Reserva extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');  // Aquí indicamos que cada reserva pertenece a un solo usuario
    }

    public function reserveDetails()
    {
        return $this->hasMany(ReserveDetail::class, 'reserva_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'reserva_id');
    }

    // Relación: Una asociación tiene muchas imágenes asociadas
    public function imgService()
    {
        return $this->hasMany(ImgService::class, 'service_id');
    }
}
