@extends('layouts.app')

@section('title', 'Editar persona')

@section('page_title', '✏️ Editar persona: ' . $person->nombre)

@section('content')
    <div class="card">
        <form action="{{ route('personas.update', $person) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group full">
                    <label for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $person->nombre) }}" required>
                    @error('nombre') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $person->email) }}">
                    @error('email') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" value="{{ old('telefono', $person->telefono) }}">
                    @error('telefono') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
                <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
