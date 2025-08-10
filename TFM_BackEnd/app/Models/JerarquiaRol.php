<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JerarquiaRol extends Model
{
    use SoftDeletes;

    protected $table = 'jerarquia_roles';

    protected $fillable = [
        'id_jerarquia',
        'id_rol',
        'id_rol_superior',
    ];

    // Relación con la jerarquía inicial
    public function jerarquia()
    {
        return $this->belongsTo(JerarquiaInicial::class, 'id_jerarquia');
    }

    // Relación con el rol principal
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'id_rol');
    }

    // Relación con el rol superior
    public function rolSuperior()
    {
        return $this->belongsTo(Roles::class, 'id_rol_superior');
    }
}
