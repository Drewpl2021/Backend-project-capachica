<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    // Definir la clave primaria como UUID
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    // Definir qué campos pueden ser asignados masivamente
    protected $fillable = ['code', 'name', 'status'];

    // Asegurarse de que la fecha sea gestionada correctamente
    protected $dates = ['deleted_at'];
}
