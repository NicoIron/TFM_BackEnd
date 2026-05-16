<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyectos extends Model
{
    use SoftDeletes;

    protected $table = 'proyectos';

    protected $fillable = [
        'id_proyecto',
        'id_organizacion',
        'nombre_proyecto',
        'descripcion',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }

    public function usuarios()
    {
        return $this->hasMany(ProyectoUsuario::class, 'id_proyecto', 'id_proyecto');
    }
}
