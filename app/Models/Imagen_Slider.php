<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Imagen_Slider extends Model
{
    use HasFactory;
    protected $fillable = ['url_image', 'estado', 'codigo'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
    public function slider()
    {
        return $this->belongsTo(Slider_Muni::class, 'slider_id');
    }
}
