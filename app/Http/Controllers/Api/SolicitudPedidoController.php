<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActualizarSolicitudRequest;
use App\Http\Requests\CrearSolicitudRequest;
use App\Models\SolicitudPedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SolicitudPedidoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // La paginación evita devolver todas las filas en una sola respuesta.
        $cantidadPagina = $request->integer('per_page', 10);
        $cantidadPagina = max(1, min($cantidadPagina, 50));

        $solicitudes = SolicitudPedido::query()
            ->orderByDesc('id')
            ->paginate($cantidadPagina)
            ->withQueryString();

        return response()->json($solicitudes);
    }

    public function show(SolicitudPedido $solicitud): JsonResponse
    {
        return response()->json(['data' => $solicitud]);
    }

    public function store(CrearSolicitudRequest $request): JsonResponse
    {
        $solicitud = SolicitudPedido::create($request->validated());

        return response()
            ->json(['mensaje' => 'Solicitud creada.', 'data' => $solicitud], 201)
            ->header('Location', url('/api/solicitudes/'.$solicitud->id));
    }

    public function update(ActualizarSolicitudRequest $request, SolicitudPedido $solicitud): JsonResponse
    {
        $solicitud->update($request->validated());

        return response()->json([
            'mensaje' => 'Solicitud actualizada.',
            'data' => $solicitud->fresh(),
        ]);
    }

    public function destroy(SolicitudPedido $solicitud): Response
    {
        $solicitud->delete();

        return response()->noContent();
    }
}
