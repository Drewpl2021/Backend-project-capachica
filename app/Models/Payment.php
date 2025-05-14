<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['id'];

    // Definir los campos que son asignables en masa
    protected $fillable = [
        'code',
        'total',
        'bi',
        'igv',
    ];
    public function sales()
    {
        return $this->hasMany(Sale::class, 'payment_id');
    }
}
