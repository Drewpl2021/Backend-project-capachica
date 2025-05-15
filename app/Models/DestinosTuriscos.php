<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DestinosTuriscos extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'nombre',
        'descripcion',
        'lugar',
        'emprendedor_id'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();  // Generar un UUID cuando se crea el modelo
        });
    }
    public function emprendedor()
    {
        return $this->belongsTo(Emprendedor::class);
    }
}
