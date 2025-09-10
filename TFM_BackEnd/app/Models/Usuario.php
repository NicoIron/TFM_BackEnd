<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JerarquiaInicial;

class Usuario extends Authenticatable
{
    // Traits necesarios para autenticación con Sanctum, notificaciones y soft deletes
    use HasApiTokens, Notifiable, SoftDeletes;

    // Especifica la tabla en la base de datos asociada a este modelo
    protected $table = 'usuarios';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellido',
        'email',
        'password_hash',
        'username',
        'id_rol',
        'id_organizacion',
        'id_jerarquia',
    ];

    /**
     * Relación inversa: un usuario pertenece a un rol
     */
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'id_rol');
    }

    /**
     * Relación inversa: un usuario pertenece a una organización
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }

    /**
     * Relación inversa: un usuario pertenece a una jerarquía
     */
    public function jerarquia()
    {
        return $this->belongsTo(JerarquiaInicial::class, 'id_jerarquia');
    }

    /**
     * Método para que Laravel sepa qué campo usar como contraseña
     * En este caso usamos 'password_hash' en lugar de 'password'
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
