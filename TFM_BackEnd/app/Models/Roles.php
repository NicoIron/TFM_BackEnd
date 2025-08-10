<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'id_rol',
        'nombre_rol',
        'nivel',
        'id_organizacion',
        'id_jerarquia',
    ];

    // Relación con organización
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }

    // Relación con jerarquía inicial
    public function jerarquia()
    {
        return $this->belongsTo(JerarquiaInicial::class, 'id_jerarquia');
    }

    // Relaciones jerarquia_roles donde este rol es principal
    public function jerarquiaRoles()
    {
        return $this->hasMany(JerarquiaRol::class, 'id_rol');
    }

    // Relaciones jerarquia_roles donde este rol es superior (jefe inmediato)
    public function rolesSuperiores()
    {
        return $this->hasMany(JerarquiaRol::class, 'id_rol_superior');
    }
}
