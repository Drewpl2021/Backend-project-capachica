<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
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
