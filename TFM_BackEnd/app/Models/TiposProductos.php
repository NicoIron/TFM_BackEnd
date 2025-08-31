<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposProductos extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'tipo_productos';

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'id_producto',
        'id_organizacion',
        'nombre_producto',
        'descripcion'
    ];

    /**
     * Relación inversa: Pertenece a una organización.
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion');
    }



    /**
     * Relación uno a muchos: Este tipo tiene muchos tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Tickets::class, 'id_tipo_producto');
    }
}
