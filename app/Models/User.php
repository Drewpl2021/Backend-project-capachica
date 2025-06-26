<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany roles()
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $guarded = ['id'];
    use SoftDeletes;
    protected $guard_name = 'api';


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
        'imagen_url'
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
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->email,
            'code' => $this->code,
            'imagen_url' => $this->imagen_url,
            'created_at' => $this->created_at,

            // Traer todos los roles del usuario
            'roles' => $this->roles->pluck('name'),  // Devuelve una colección con todos los nombres de los roles

            // Traer todos los permisos del usuario
            'permissions' => $this->getPermissionsViaRoles()->pluck('name'),  // Devuelve todos los permisos relacionados con los roles
        ];
    }

    // Relación muchos a muchos con Emprendedor
    public function emprendedores()
    {
        return $this->belongsToMany(
            Emprendedor::class,
            'emprendedor_user',
            'user_id',
            'emprendedor_id'
        )->withTimestamps(); // esto es opcional si tienes timestamps en la tabla pivotes
    }


    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'user_id');  // Aquí indicamos que un usuario puede tener muchas reservas
    }
}
