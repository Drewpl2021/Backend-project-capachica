<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ImgEmprendedor extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'img_emprendedores';  // Aquí el nombre que usaste en migración
    protected $fillable = ['emprendedor_id', 'imagen_url', 'description', 'code'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function services()
    {
        return $this->belongsTo(Emprendedor::class);
    }
}
