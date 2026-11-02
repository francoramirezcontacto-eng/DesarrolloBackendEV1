<?php

namespace Tests\Feature;

use App\Models\SolicitudPedido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolicitudPedidoApiTest extends TestCase
{
    use RefreshDatabase;

    private function datosPrueba(): array
    {
        return [
            'cliente_referencia' => 'Cliente de prueba',
            'cantidad' => 5,
            'fecha_requerida' => '2026-11-15',
            'estado' => 'pendiente',
        ];
    }

    public function test_crea_y_consulta_una_solicitud(): void
    {
        $respuesta = $this->postJson('/api/solicitudes', $this->datosPrueba())
            ->assertCreated()
            ->assertJsonPath('data.cliente_referencia', 'Cliente de prueba');

        $id = $respuesta->json('data.id');

        $this->assertDatabaseHas('solicitud_pedidos', ['id' => $id, 'cantidad' => 5]);
        $this->getJson('/api/solicitudes/'.$id)
            ->assertOk()
            ->assertJsonPath('data.estado', 'pendiente');
    }

    public function test_actualiza_con_put_y_patch(): void
    {
        $solicitud = SolicitudPedido::create($this->datosPrueba());
        $nuevosDatos = [
            'cliente_referencia' => 'Cliente dos',
            'cantidad' => 12,
            'fecha_requerida' => '2026-11-20',
            'estado' => 'en_revision',
        ];

        $this->putJson('/api/solicitudes/'.$solicitud->id, $nuevosDatos)
            ->assertOk()->assertJsonPath('data.cantidad', 12);

        $this->patchJson('/api/solicitudes/'.$solicitud->id, ['estado' => 'finalizada'])
            ->assertOk()->assertJsonPath('data.estado', 'finalizada');

        $this->assertDatabaseHas('solicitud_pedidos', [
            'id' => $solicitud->id,
            'cantidad' => 12,
            'estado' => 'finalizada',
        ]);
    }

    public function test_elimina_y_despues_responde_404(): void
    {
        $solicitud = SolicitudPedido::create($this->datosPrueba());
        $this->deleteJson('/api/solicitudes/'.$solicitud->id)->assertNoContent();
        $this->getJson('/api/solicitudes/'.$solicitud->id)->assertNotFound();
        $this->assertDatabaseMissing('solicitud_pedidos', ['id' => $solicitud->id]);
    }

    public function test_valida_cantidad_y_estado(): void
    {
        $datos = $this->datosPrueba();
        $datos['cantidad'] = -3;
        $datos['estado'] = 'despachado';

        $this->postJson('/api/solicitudes', $datos)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cantidad', 'estado']);

        $this->assertDatabaseCount('solicitud_pedidos', 0);
    }

    public function test_exige_campos_en_put(): void
    {
        $solicitud = SolicitudPedido::create($this->datosPrueba());
        $this->putJson('/api/solicitudes/'.$solicitud->id, ['estado' => 'finalizada'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cliente_referencia', 'cantidad', 'fecha_requerida']);
    }

    public function test_listado_paginado_limitado_a_50(): void
    {
        for ($n = 1; $n <= 60; $n++) {
            SolicitudPedido::create($this->datosPrueba());
        }

        $this->getJson('/api/solicitudes')->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('total', 60);

        $this->getJson('/api/solicitudes?per_page=500')->assertOk()
            ->assertJsonCount(50, 'data')
            ->assertJsonPath('per_page', 50);
    }
}
