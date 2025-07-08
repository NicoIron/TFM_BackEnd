<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketsLogs extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'tickets_logs';

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'id_ticket',
        'action',
        'eliminado',
    ];

    /**
     * Relación inversa: Este log pertenece a un ticket.
     */
    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'id_ticket');
    }
}
