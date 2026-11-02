<?php

namespace Database\Seeders;

use App\Models\SolicitudPedido;
use Illuminate\Database\Seeder;

class SolicitudPedidoSeeder extends Seeder
{
    public function run(): void
    {
        // Los 60 registros son ficticios y sirven para comprobar la paginación.
        for ($numero = 1; $numero <= 60; $numero++) {
            SolicitudPedido::create([
                'cliente_referencia' => 'Cliente de prueba '.$numero,
                'cantidad' => ($numero % 15) + 1,
                'fecha_requerida' => '2026-11-15',
                'estado' => 'pendiente',
            ]);
        }
    }
}
