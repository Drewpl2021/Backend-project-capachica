<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'modules';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'subtitle',
        'type',
        'code',
        'icon',
        'status',
        'moduleOrder',
        'link',
        'parent_module_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->status = true;
        });
    }

    public function parentModule()
    {
        return $this->belongsTo(ParentModule::class, 'parent_module_id');
    }
}
