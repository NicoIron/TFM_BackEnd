<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Notificaciones extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notificaciones';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'id_notificacion',
        'id_usuario',
        'id_organizacion',
        'tipo_notificacion',
        'titulo',
        'mensaje',
        'id_ticket',
        'leida',
        'fecha_creacion',
        'fecha_lectura',
    ];

    protected $dates = [
        'fecha_creacion',
        'fecha_lectura',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }

    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'id_ticket', 'id_ticket');
    }

    // Scopes
    public function scopeLeidas($query)
    {
        return $query->where('leida', 1);
    }
}
