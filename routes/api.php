<?php

use App\Http\Controllers\Api\SolicitudPedidoController;
use Illuminate\Support\Facades\Route;

// Rutas de solicitudes de pedido para las pruebas de la Unidad 1.
Route::get('/solicitudes', [SolicitudPedidoController::class, 'index']);
Route::get('/solicitudes/{solicitud}', [SolicitudPedidoController::class, 'show']);
Route::post('/solicitudes', [SolicitudPedidoController::class, 'store']);
Route::put('/solicitudes/{solicitud}', [SolicitudPedidoController::class, 'update']);
Route::patch('/solicitudes/{solicitud}', [SolicitudPedidoController::class, 'update']);
Route::delete('/solicitudes/{solicitud}', [SolicitudPedidoController::class, 'destroy']);
