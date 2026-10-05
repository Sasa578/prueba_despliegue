<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::withCount('payments')
            ->orderBy('nombre')
            ->get();

        return view('services.index', compact('services'));
    }

    public function create(): View
    {
        return view('services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'categoria' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:1000',
            'costo_mensual' => 'required|numeric|min:0',
            'dia_vencimiento' => 'required|integer|between:1,31',
            'activo' => 'sometimes|boolean',
        ], [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'categoria.required' => 'Selecciona una categoría.',
            'costo_mensual.required' => 'El costo mensual es obligatorio.',
            'costo_mensual.numeric' => 'El costo mensual debe ser un número.',
            'dia_vencimiento.required' => 'El día de vencimiento es obligatorio.',
            'dia_vencimiento.between' => 'El día de vencimiento debe estar entre 1 y 31.',
        ]);

        $validated['activo'] = $request->boolean('activo');

        Service::create($validated);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio "' . $validated['nombre'] . '" creado correctamente.');
    }

    public function edit(Service $servicio): View
    {
        return view('services.edit', ['service' => $servicio]);
    }

    public function update(Request $request, Service $servicio): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'categoria' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:1000',
            'costo_mensual' => 'required|numeric|min:0',
            'dia_vencimiento' => 'required|integer|between:1,31',
            'activo' => 'sometimes|boolean',
        ], [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'categoria.required' => 'Selecciona una categoría.',
            'costo_mensual.required' => 'El costo mensual es obligatorio.',
            'costo_mensual.numeric' => 'El costo mensual debe ser un número.',
            'dia_vencimiento.required' => 'El día de vencimiento es obligatorio.',
            'dia_vencimiento.between' => 'El día de vencimiento debe estar entre 1 y 31.',
        ]);

        $validated['activo'] = $request->boolean('activo');

        $servicio->update($validated);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio "' . $servicio->nombre . '" actualizado correctamente.');
    }

    public function destroy(Service $servicio): RedirectResponse
    {
        $nombre = $servicio->nombre;
        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio "' . $nombre . '" eliminado (junto con sus pagos).');
    }
}
