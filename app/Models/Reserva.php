<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $incrementing = false;


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
}
