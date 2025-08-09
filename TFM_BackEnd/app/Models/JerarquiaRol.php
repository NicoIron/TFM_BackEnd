<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JerarquiaRol extends Model
{
    protected $table = 'jerarquia_roles';

    protected $fillable = [
        'nombre',
        'id_rol_superior',
        'nivel'
    ];

    public function rolSuperior(): BelongsTo
    {
        return $this->belongsTo(JerarquiaRol::class, 'id_rol_superior');
    }

    public function rolesSubordinados()
    {
        return $this->hasMany(Roles::class, 'id_jerarquia');
    }
}
