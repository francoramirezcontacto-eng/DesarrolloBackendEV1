<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudPedido extends Model
{
    protected $table = 'solicitud_pedidos';

    protected $fillable = [
        'cliente_referencia',
        'cantidad',
        'fecha_requerida',
        'estado',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'fecha_requerida' => 'date:Y-m-d',
    ];
}
