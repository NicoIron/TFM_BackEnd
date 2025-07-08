<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    //  Nombre de la tabla asociada (por si Laravel no la detecta automáticamente)
    protected $table = 'usuarios';

    //  Permitir asignación masiva solo en estos campos
    // Esto protege tu modelo frente a inserciones no deseadas
    protected $fillable = [
        'id_organizacion',
        'id_rol',
        'id_usuario',
        'nombre',
        'apellido',
        'proyecto',
        'id_empleado',
        'correo',
        'contraseña',
        'eliminado'
    ];

    //  Oculta campos sensibles al convertir el modelo en JSON
    protected $hidden = [
        'contraseña'
    ];

    //  Define los tipos de datos para ciertos campos
    protected $casts = [
        'eliminado' => 'boolean'
    ];

    //  Laravel actualizará automáticamente estos campos si existen en la tabla
    public $timestamps = true;

    // Relación: Un usuario pertenece a una organización
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion');
    }

    // Relación: Un usuario tiene un rol
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'id_rol');
    }

    // Relación: Un usuario puede tener muchos tickets
    public function tickets()
    {
        return $this->hasMany(Tickets::class, 'id_usuario');
    }
}
