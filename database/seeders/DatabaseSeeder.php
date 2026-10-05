<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Person;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Datos de ejemplo para probar la aplicación.
     * Es idempotente: no duplica registros si ya existen.
     */
    public function run(): void
    {
        // ---- Personas ----
        $carlos = Person::firstOrCreate(['nombre' => 'Carlos Pérez'], ['email' => 'carlos@example.com', 'telefono' => '77123456']);
        $maria = Person::firstOrCreate(['nombre' => 'María López'], ['email' => 'maria@example.com', 'telefono' => '77876543']);
        $ana = Person::firstOrCreate(['nombre' => 'Ana Torres'], ['email' => 'ana@example.com', 'telefono' => '75432109']);
        $luis = Person::firstOrCreate(['nombre' => 'Luis Gómez'], ['email' => 'luis@example.com', 'telefono' => '76234567']);

        // ---- Servicios ----
        $luz = Service::firstOrCreate(
            ['nombre' => 'Luz'],
            ['categoria' => 'servicios_basicos', 'costo_mensual' => 250.00, 'dia_vencimiento' => 10, 'descripcion' => 'Factura de electricidad']
        );
        $agua = Service::firstOrCreate(
            ['nombre' => 'Agua'],
            ['categoria' => 'servicios_basicos', 'costo_mensual' => 120.00, 'dia_vencimiento' => 15, 'descripcion' => 'Servicio de agua potable']
        );
        $gas = Service::firstOrCreate(
            ['nombre' => 'Gas'],
            ['categoria' => 'servicios_basicos', 'costo_mensual' => 85.00, 'dia_vencimiento' => 20, 'descripcion' => 'Garrafa de gas domiciliaria']
        );
        $internet = Service::firstOrCreate(
            ['nombre' => 'Internet'],
            ['categoria' => 'internet_tv', 'costo_mensual' => 300.00, 'dia_vencimiento' => 5, 'descripcion' => 'Fibra óptica 100 Mbps']
        );
        $disney = Service::firstOrCreate(
            ['nombre' => 'Disney+'],
            ['categoria' => 'streaming', 'costo_mensual' => 45.00, 'dia_vencimiento' => 12, 'descripcion' => 'Suscripción estándar']
        );
        $netflix = Service::firstOrCreate(
            ['nombre' => 'Netflix'],
            ['categoria' => 'streaming', 'costo_mensual' => 60.00, 'dia_vencimiento' => 3, 'descripcion' => 'Plan estándar HD']
        );
        $spotify = Service::firstOrCreate(
            ['nombre' => 'Spotify Familiar'],
            ['categoria' => 'streaming', 'costo_mensual' => 75.00, 'dia_vencimiento' => 8, 'descripcion' => 'Plan familiar entre 4 personas']
        );

        // ---- Pagos de ejemplo (solo si no hay ninguno) ----
        if (Payment::count() === 0) {
            $hoy = now();

            // Spotify Familiar pagado entre varias personas (plan compartido)
            $pagoSpotify = Payment::create([
                'service_id' => $spotify->id,
                'fecha_pago' => $hoy->copy()->subDays(2),
                'monto_total' => 75.00,
                'estado' => 'pagado',
                'notas' => 'Aporte dividido entre los 4 miembros del plan',
            ]);
            $pagoSpotify->people()->attach($carlos->id, ['monto_aportado' => 18.75]);
            $pagoSpotify->people()->attach($maria->id, ['monto_aportado' => 18.75]);
            $pagoSpotify->people()->attach($ana->id, ['monto_aportado' => 18.75]);
            $pagoSpotify->people()->attach($luis->id, ['monto_aportado' => 18.75]);

            // Internet pagado por una sola persona
            $pagoInternet = Payment::create([
                'service_id' => $internet->id,
                'fecha_pago' => $hoy->copy()->subDays(5),
                'monto_total' => 300.00,
                'estado' => 'pagado',
                'notas' => null,
            ]);
            $pagoInternet->people()->attach($carlos->id, ['monto_aportado' => 300.00]);

            // Luz pendiente
            Payment::create([
                'service_id' => $luz->id,
                'fecha_pago' => $hoy->copy()->subDays(1),
                'monto_total' => 250.00,
                'estado' => 'pendiente',
                'notas' => 'Falta pasar por la oficina a pagar',
            ]);

            // Netflix vencido
            Payment::create([
                'service_id' => $netflix->id,
                'fecha_pago' => $hoy->copy()->subDays(10),
                'monto_total' => 60.00,
                'estado' => 'vencido',
                'notas' => 'Se olvidó renovar la tarjeta',
            ]);
        }
    }
}
