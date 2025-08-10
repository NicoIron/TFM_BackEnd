<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JerarquiaInicial extends Model
{
    use SoftDeletes;

    protected $table = 'jerarquia_inicial';

    protected $fillable = [
        'id_jerarquia',
        'id_organizacion',
        'cargo',
    ];

    /**
     * Relación con Organizacion
     * Una jerarquía inicial pertenece a una organización
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }
}
