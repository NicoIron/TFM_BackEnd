<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProyectoUsuario extends Model
{
    use SoftDeletes;

    protected $table = 'proyecto_usuarios';

    protected $fillable = [
        'id_proyecto',
        'id_usuario',
        'id_organizacion',
    ];
}
