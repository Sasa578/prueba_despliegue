<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Person;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::with(['service', 'people'])->orderByDesc('fecha_pago');

        // Filtros simples para la práctica
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->input('service_id'));
        }

        $payments = $query->paginate(15)->withQueryString();
        $services = Service::orderBy('nombre')->get();

        return view('payments.index', compact('payments', 'services'));
    }

    public function create(): View
    {
        $services = Service::where('activo', true)->orderBy('nombre')->get();
        $people = Person::orderBy('nombre')->get();

        return view('payments.create', compact('services', 'people'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(), $this->validationMessages());

        $payment = Payment::create([
            'service_id' => $validated['service_id'],
            'fecha_pago' => $validated['fecha_pago'],
            'monto_total' => $validated['monto_total'],
            'estado' => $validated['estado'],
            'notas' => $validated['notas'] ?? null,
        ]);

        $this->guardarParticipantes($payment, $request->input('participantes', []));

        return redirect()->route('pagos.show', $payment)
            ->with('success', 'Pago registrado correctamente.');
    }

    public function show(Payment $pago): View
    {
        $pago->load(['service', 'people']);

        return view('payments.show', ['payment' => $pago]);
    }

    public function edit(Payment $pago): View
    {
        $pago->load(['service', 'people']);
        $services = Service::orderBy('nombre')->get();
        $people = Person::orderBy('nombre')->get();

        return view('payments.edit', ['payment' => $pago, 'services' => $services, 'people' => $people]);
    }

    public function update(Request $request, Payment $pago): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(), $this->validationMessages());

        $pago->update([
            'service_id' => $validated['service_id'],
            'fecha_pago' => $validated['fecha_pago'],
            'monto_total' => $validated['monto_total'],
            'estado' => $validated['estado'],
            'notas' => $validated['notas'] ?? null,
        ]);

        // Se reemplazan los participantes por los enviados en el formulario
        $pago->people()->detach();
        $this->guardarParticipantes($pago, $request->input('participantes', []));

        return redirect()->route('pagos.show', $pago)
            ->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Payment $pago): RedirectResponse
    {
        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado correctamente.');
    }

    /**
     * Reglas de validación para crear/editar un pago.
     */
    private function validationRules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'fecha_pago' => 'required|date',
            'monto_total' => 'required|numeric|min:0',
            'estado' => 'required|in:pagado,pendiente,vencido',
            'notas' => 'nullable|string|max:1000',
            'participantes' => 'nullable|array',
            'participantes.*.person_id' => 'required_with:participantes|exists:people,id',
            'participantes.*.monto_aportado' => 'required_with:participantes|numeric|min:0',
        ];
    }

    /**
     * Mensajes de validación en español.
     */
    private function validationMessages(): array
    {
        return [
            'service_id.required' => 'Selecciona un servicio.',
            'service_id.exists' => 'El servicio seleccionado no existe.',
            'fecha_pago.required' => 'La fecha del pago es obligatoria.',
            'fecha_pago.date' => 'La fecha del pago no es válida.',
            'monto_total.required' => 'El monto total es obligatorio.',
            'monto_total.numeric' => 'El monto total debe ser un número.',
            'estado.required' => 'Selecciona el estado del pago.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'participantes.*.person_id.exists' => 'Una de las personas seleccionadas no existe.',
            'participantes.*.monto_aportado.numeric' => 'Los montos aportados deben ser números.',
        ];
    }

    /**
     * Guarda los participantes del pago (soporta varias personas por servicio,
     * p. ej. un plan familiar de Spotify pagado entre varios).
     */
    private function guardarParticipantes(Payment $payment, array $participantes): void
    {
        foreach ($participantes as $participante) {
            $personId = $participante['person_id'] ?? null;
            $monto = $participante['monto_aportado'] ?? null;

            if ($personId && $monto !== null && $monto !== '') {
                $payment->people()->attach($personId, ['monto_aportado' => $monto]);
            }
        }
    }
}
