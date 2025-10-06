<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizacion extends Model
{
    use SoftDeletes;

    protected $table = 'organizacion';

    protected $fillable = [
        'id_organizacion',
        'nombre_organizacion',
        'descripcion',
    ];

    /* Relación uno a muchos: Una organización tiene muchas jerarquías */
    public function jerarquias()
    {
        return $this->hasMany(JerarquiaInicial::class, 'id_organizacion');
    }

    /* Relación uno a muchos: Una organización tiene muchos roles */
    public function roles()
    {
        return $this->hasMany(Roles::class, 'id_organizacion');
    }

    /* Relación uno a muchos: Una organización tiene muchos usuarios */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_organizacion');
    }

    /* Relación uno a muchos: Una organización tiene muchos tipos de productos */
    public function tiposProductos()
    {
        return $this->hasMany(TiposProductos::class, 'id_organizacion');
    }
}
