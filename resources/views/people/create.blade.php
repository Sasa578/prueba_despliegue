@extends('layouts.app')

@section('title', 'Nueva persona')

@section('page_title', '👥 Nueva persona')

@section('content')
    <div class="card">
        <form action="{{ route('personas.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group full">
                    <label for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                           placeholder="Ej: Carlos Pérez" required>
                    @error('nombre') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           placeholder="ejemplo@correo.com">
                    @error('email') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" value="{{ old('telefono') }}"
                           placeholder="Ej: 77123456">
                    @error('telefono') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar persona</button>
                <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
