<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $guard_name = 'api';
    protected $fillable = [
        'name',
        'guard_name',
        'description', // ✅ Necesario para que se guarde
    ];

     // Define la relación con módulos
     public function modules()
       {
          return $this->belongsToMany(Module::class, 'module_role', 'role_id', 'module_id');
       }
}
