<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tickets extends Model
{
    use SoftDeletes;

    protected $table = 'tickets';

    protected $fillable = [
        'id_ticket',
        'id_organizacion',
        'id_usuario',
        'id_tipo_producto',
        'monto',
        'proyecto',
        'descr_compra',
        'estado_ticket',
        'fecha_cierre',
    ];

    // Relación con organización
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Relación con tipo producto
    public function tipoProducto()
    {
        return $this->belongsTo(TiposProductos::class, 'id_tipo_producto', 'id_producto');
    }
}
