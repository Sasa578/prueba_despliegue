<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Person;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaPagosTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_dashboard_muestra_los_servicios_activos(): void
    {
        Service::create([
            'nombre' => 'Luz',
            'categoria' => 'servicios_basicos',
            'costo_mensual' => 250,
            'dia_vencimiento' => 10,
            'activo' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Luz');
    }

    public function test_se_puede_registrar_un_servicio(): void
    {
        $response = $this->post('/servicios', [
            'nombre' => 'Netflix',
            'categoria' => 'streaming',
            'costo_mensual' => 60,
            'dia_vencimiento' => 3,
            'activo' => 1,
        ]);

        $response->assertRedirect(route('servicios.index'));

        $this->assertDatabaseHas('services', [
            'nombre' => 'Netflix',
            'costo_mensual' => 60,
        ]);
    }

    public function test_se_puede_registrar_un_pago_compartido_entre_varias_personas(): void
    {
        $carlos = Person::create(['nombre' => 'Carlos Pérez']);
        $maria = Person::create(['nombre' => 'María López']);
        $spotify = Service::create([
            'nombre' => 'Spotify Familiar',
            'categoria' => 'streaming',
            'costo_mensual' => 75,
            'dia_vencimiento' => 8,
            'activo' => true,
        ]);

        $response = $this->post('/pagos', [
            'service_id' => $spotify->id,
            'fecha_pago' => now()->format('Y-m-d'),
            'monto_total' => 75,
            'estado' => 'pagado',
            'notas' => 'Plan familiar',
            'participantes' => [
                ['person_id' => $carlos->id, 'monto_aportado' => 37.50],
                ['person_id' => $maria->id, 'monto_aportado' => 37.50],
            ],
        ]);

        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseCount('payment_person', 2);

        $payment = Payment::first();
        $response->assertRedirect(route('pagos.show', $payment));

        $this->assertEquals(2, $payment->people()->count());
        $this->assertEquals(75.0, $payment->totalAportado());
    }

    public function test_no_se_puede_eliminar_una_persona_con_pagos_asociados(): void
    {
        $carlos = Person::create(['nombre' => 'Carlos Pérez']);
        $luz = Service::create([
            'nombre' => 'Luz',
            'categoria' => 'servicios_basicos',
            'costo_mensual' => 250,
            'dia_vencimiento' => 10,
            'activo' => true,
        ]);

        $pago = Payment::create([
            'service_id' => $luz->id,
            'fecha_pago' => now(),
            'monto_total' => 250,
            'estado' => 'pagado',
        ]);
        $pago->people()->attach($carlos->id, ['monto_aportado' => 250]);

        $response = $this->delete(route('personas.destroy', $carlos));

        $response->assertRedirect(route('personas.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('people', ['id' => $carlos->id, 'nombre' => 'Carlos Pérez']);
    }
}
