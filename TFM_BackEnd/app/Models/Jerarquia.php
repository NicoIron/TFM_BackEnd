<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jerarquia extends Model
{
    protected $table = 'jerarquia'; // o 'jerarquias', si así se llama la tabla real

    protected $fillable = [
        'id_organizacion',
        'cargo',
        'eliminado',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion');
    }

    public function roles()
    {
        return $this->hasMany(Roles::class, 'id_jerarquia');
    }
}
