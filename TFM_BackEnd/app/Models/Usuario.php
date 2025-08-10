<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JerarquiaInicial;

class Usuario extends Model
{
    use SoftDeletes;

    protected $table = 'usuarios';

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

    /* Relación inversa: Un usuario pertenece a un rol */
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'id_rol');
    }

    /* Relación inversa: Un usuario pertenece a una organización */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }

    /* Relación inversa: Un usuario pertenece a una jerarquía */
    public function jerarquia()
    {
        return $this->belongsTo(JerarquiaInicial::class, 'id_jerarquia');
    }
}
