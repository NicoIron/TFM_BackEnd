<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketsLogs extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tickets_logs';

    protected $fillable = [
        'id_ticket_log',
        'id_ticket',
        'id_usuario',
        'estado_anterior',
        'estado_nuevo',
        'fecha_cambio'
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
        'eliminado' => 'boolean'
    ];

    /**
     * Relación: Este log pertenece a un ticket.
     */
    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'id_ticket', 'id_ticket');
    }

    /**
     * Relación: Este log pertenece a un usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
