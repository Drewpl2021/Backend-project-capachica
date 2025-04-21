<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ParentModule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parent_modules';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'code',
        'subtitle',
        'type',
        'icon',
        'status',
        'moduleOrder',
        'link',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->status = true;
        });
    }

    // Relación con el modelo 'Module'
    public function modules()
    {
        return $this->hasMany(Module::class, 'parent_module_id');
    }

    // Convertir 'created_at' a 'createdAt'
    public function getCreatedAtAttribute($value)
    {
        return $this->asDateTime($value)->format('Y-m-d\TH:i:s.v\Z');
    }

    // Convertir 'updated_at' a 'updatedAt'
    public function getUpdatedAtAttribute($value)
    {
        return $this->asDateTime($value)->format('Y-m-d\TH:i:s.v\Z');
    }

    // Convertir 'deleted_at' a 'deletedAt'
    public function getDeletedAtAttribute($value)
    {
        return $value ? $this->asDateTime($value)->format('Y-m-d\TH:i:s.v\Z') : null;
    }

    // Convertir 'status' de 1/0 a booleano
    public function getStatusAttribute($value)
    {
        return (bool) $value;
    }
}
