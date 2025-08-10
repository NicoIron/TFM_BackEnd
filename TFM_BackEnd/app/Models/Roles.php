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
        'jefe_inmediato',
        'id_organizacion',
        'id_jerarquia',
    ];

    /**
     * Relación inversa: Este rol pertenece a una organización
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }

    /**
     * Relación inversa: Este rol pertenece a una jerarquía inicial
     */
    public function jerarquia()
    {
        return $this->belongsTo(JerarquiaInicial::class, 'id_jerarquia', 'id');
    }

    /**
     * Relación uno a muchos: Un rol tiene muchos usuarios
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
    }
}
