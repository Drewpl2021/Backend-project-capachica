<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ImgEmprendedorService extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'img_emprenpedor_service'; // cuidado con el nombre (corrige si lo escribiste mal)

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    protected $fillable = [
        'emprendedor_service_id',
        'url_image',
        'description',
        'estado',
        'code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    // Relación inversa: pertenece a un emprendedor_service
    public function emprendedorService()
    {
        return $this->belongsTo(EmprendedorService::class, 'emprendedor_service_id');
    }
}
