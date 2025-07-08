<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
          'id_organizacion',
        'id_ticket',
        'id_rol',
        'id_usuario',
        'id_tipo_producto',
        'monto',
        'proyecto',
        'desc_compra',
        'gestor',
        'estado_solicitud',
        'fecha_limite',
        'num_ticket',
        'eliminado',
    ];


    /**
     * Relación inversa: El ticket pertenece a una organización.
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion');
    }

    /**
     * Relación inversa: El ticket fue creado por un usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Relación inversa: El ticket está asociado a un rol.
     */
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'id_rol');
    }

    /**
     * Relación inversa: El ticket pertenece a un tipo de producto.
     */
    public function tipoProducto()
    {
        return $this->belongsTo(TiposProductos::class, 'id_tipo_producto');
    }

    /**
     * Relación uno a muchos: Un ticket tiene muchos logs.
     */
    public function logs()
    {
        return $this->hasMany(TicketsLogs::class, 'id_ticket');
    }

}
