<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Panel principal: vencimientos, pagos del mes y resumen.
     */
    public function index()
    {
        $hoy = now();

        // Próximos vencimientos de servicios activos
        $servicios = Service::where('activo', true)->orderBy('nombre')->get();

        $vencimientos = $servicios->map(function (Service $service) use ($hoy) {
            $fechaVencimiento = $this->calcularProximaFechaVencimiento($service, $hoy);

            $pagoDelMes = $service->payments()
                ->where('estado', 'pagado')
                ->whereYear('fecha_pago', $hoy->year)
                ->whereMonth('fecha_pago', $hoy->month)
                ->exists();

            $estado = $pagoDelMes
                ? 'pagado'
                : ($fechaVencimiento->lt($hoy->startOfDay()) ? 'vencido' : 'pendiente');

            return (object) [
                'service' => $service,
                'fecha_vencimiento' => $fechaVencimiento,
                'estado' => $estado,
            ];
        })->sortBy('fecha_vencimiento')->values();

        // Pagos registrados en el mes actual
        $pagosDelMes = Payment::with(['service', 'people'])
            ->whereYear('fecha_pago', $hoy->year)
            ->whereMonth('fecha_pago', $hoy->month)
            ->orderByDesc('fecha_pago')
            ->get();

        $totalPagadoMes = Payment::where('estado', 'pagado')
            ->whereYear('fecha_pago', $hoy->year)
            ->whereMonth('fecha_pago', $hoy->month)
            ->sum('monto_total');

        return view('dashboard', compact('vencimientos', 'pagosDelMes', 'totalPagadoMes'));
    }

    /**
     * Calcula la próxima fecha de vencimiento de un servicio a partir de su día de corte.
     */
    private function calcularProximaFechaVencimiento(Service $service, Carbon $hoy): Carbon
    {
        $dia = min($service->dia_vencimiento, $hoy->daysInMonth);
        $fecha = $hoy->copy()->day($dia);

        // Si ya pasó el día de vencimiento de este mes, se calcula para el siguiente mes
        if ($fecha->lt($hoy->startOfDay())) {
            $proximoMes = $hoy->copy()->addMonthNoOverflow();
            $fecha = $proximoMes->day(min($service->dia_vencimiento, $proximoMes->daysInMonth));
        }

        return $fecha;
    }
}
