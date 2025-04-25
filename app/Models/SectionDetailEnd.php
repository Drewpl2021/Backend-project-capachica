<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SectionDetailEnd extends Model
{
    use HasFactory, SoftDeletes;

    // Definir la clave primaria como UUID
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Definir qué campos pueden ser asignados masivamente
    protected $fillable = ['status', 'code', 'image', 'title', 'description', 'subtitle', 'section_detail_id'];

    // Asegurarse de que la fecha sea gestionada correctamente
    protected $dates = ['deleted_at'];

    // Relación: Un SectionDetailEnd pertenece a un SectionDetail
    public function sectionDetail()
    {
        return $this->belongsTo(SectionDetail::class, 'section_detail_id');
    }
}
