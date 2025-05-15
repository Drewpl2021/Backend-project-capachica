<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SectionDetail extends Model
{
    use HasFactory, SoftDeletes;

    // Definir la clave primaria como UUID
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $keyType = 'string';

    // Definir qué campos pueden ser asignados masivamente
    protected $fillable = ['status', 'code', 'title', 'description', 'section_id'];

    // Asegurarse de que la fecha sea gestionada correctamente
    protected $dates = ['deleted_at'];

    // Relación: Un SectionDetail pertenece a una Section
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function sectionDetailEnds()
    {
        return $this->hasMany(SectionDetailEnd::class, 'section_detail_id'); // Relación uno a muchos
    }
}
