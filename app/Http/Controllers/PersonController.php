<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function index(): View
    {
        $people = Person::withCount('payments')
            ->orderBy('nombre')
            ->get();

        return view('people.index', compact('people'));
    }

    public function create(): View
    {
        return view('people.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'telefono' => 'nullable|string|max:20',
        ], [
            'nombre.required' => 'El nombre de la persona es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
        ]);

        Person::create($validated);

        return redirect()->route('personas.index')
            ->with('success', 'Persona "' . $validated['nombre'] . '" registrada correctamente.');
    }

    public function edit(Person $persona): View
    {
        return view('people.edit', ['person' => $persona]);
    }

    public function update(Request $request, Person $persona): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'telefono' => 'nullable|string|max:20',
        ], [
            'nombre.required' => 'El nombre de la persona es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
        ]);

        $persona->update($validated);

        return redirect()->route('personas.index')
            ->with('success', 'Persona "' . $persona->nombre . '" actualizada correctamente.');
    }

    public function destroy(Person $persona): RedirectResponse
    {
        // Si la persona tiene pagos asociados, la BD lo impide (restrictOnDelete)
        if ($persona->payments()->exists()) {
            return redirect()->route('personas.index')
                ->with('error', 'No se puede eliminar a "' . $persona->nombre . '" porque tiene pagos asociados. Elimina primero esos pagos.');
        }

        $nombre = $persona->nombre;
        $persona->delete();

        return redirect()->route('personas.index')
            ->with('success', 'Persona "' . $nombre . '" eliminada correctamente.');
    }
}
