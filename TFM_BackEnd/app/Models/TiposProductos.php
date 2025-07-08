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
    protected $table = 'tipos_producto';

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'id_organizacion',
        'nombre',
        'descripcion',
        'id_padre',
        'eliminado',
    ];

    /**
     * Relación inversa: Pertenece a una organización.
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion');
    }

    /**
     * Relación recursiva: Tipo de producto padre.
     */
    public function padre()
    {
        return $this->belongsTo(TiposProductos::class, 'id_padre');
    }

    /**
     * Relación recursiva: Subtipos de producto.
     */
    public function hijos()
    {
        return $this->hasMany(TiposProductos::class, 'id_padre');
    }

    /**
     * Relación uno a muchos: Este tipo tiene muchos tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Tickets::class, 'id_tipo_producto');
    }
}
