<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $guarded = ['id'];
    use SoftDeletes;


    protected $table = 'users';
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'code',
        'username',
        'email',
        'password',
    ];

    /**
     * Los atributos que deberían ser ocultados en la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deberían ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Obtiene el identificador JWT del usuario.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retorna el 'id' del usuario (que es un UUID)
    }

    /**
     * Obtiene las reclamaciones personalizadas para el JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'username' => $this->username, // Agregar 'username' al token
            'email' => $this->email,       // Agregar 'email' al token
        ];
    }
    // Relación muchos a muchos con Emprendedor
    public function emprendedores()
    {
        return $this->belongsToMany(Emprendedor::class, 'emprendedor_user', 'user_id', 'emprendedor_id');
    }
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'user_id');  // Aquí indicamos que un usuario puede tener muchas reservas
    }
}
