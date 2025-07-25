<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{

    protected $table = 'roles';


    protected $fillable = [
        'id_organizacion',
        'id_jerarquia',
        'nombre_rol',
        'jefe_inmediato',
        'eliminado'
    ];

    /* Relacion inversa: Este rol pertenece a una organizacion */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion');
    }
    /* Relación inversa: Este rol pertenece a una jerarquia */
    public function jerarquia()
    {
        return $this->belongsTo(Jerarquia::class, 'id_jerarquia');
    }
    /* Relación uno a muchos: Un rol tiene muchos usuarios */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol');
    }
    //
}
