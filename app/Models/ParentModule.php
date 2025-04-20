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

    public function modules()
    {
        return $this->hasMany(Module::class, 'parent_module_id');
    }
}
