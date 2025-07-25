<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    //

    protected $table = 'organizacion';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];


    /*relacion uno a muchos: Una organizacion tiene muchas jerarquias */

    public function jerarquias()
    {
        return $this->hasMany(Jerarquia::class, 'id_organizacion');
    }

    /*relación uno a muchos: Una organizacion tiene muchos roles */
    public function roles()
    {
        return $this->hasMany(Roles::class, 'id_organizacion');
    }

    /*relación uno a muchos: Una organizacion tiene muchos usuarios */

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_organizacion');
    }

    /*relacion uno a muchos: Una organizacoin tiene muchos tipos de productos */
    public function tiposProductos()
    {
        return $this->hasMany(TiposProductos::class, 'id_organizacion');
    }

}
