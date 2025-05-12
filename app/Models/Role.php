<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $guard_name = 'api';
    protected $fillable = [
        'name',
        'guard_name',
        'description', // ✅ Necesario para que se guarde
    ];
}
