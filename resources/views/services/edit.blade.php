@extends('layouts.app')

@section('title', 'Editar servicio')

@section('page_title', '✏️ Editar servicio: ' . $service->nombre)

@section('content')
    <div class="card">
        <form action="{{ route('servicios.update', $service) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $service->nombre) }}" required>
                    @error('nombre') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría <span class="required">*</span></label>
                    <select name="categoria" id="categoria" required>
                        @foreach (\App\Models\Service::CATEGORIAS as $valor => $etiqueta)
                            <option value="{{ $valor }}"
                                {{ old('categoria', $service->categoria) === $valor ? 'selected' : '' }}>
                                {{ $etiqueta }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="costo_mensual">Costo mensual (Bs) <span class="required">*</span></label>
                    <input type="number" name="costo_mensual" id="costo_mensual" step="0.01" min="0"
                           value="{{ old('costo_mensual', $service->costo_mensual) }}" required>
                    @error('costo_mensual') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="dia_vencimiento">Día de vencimiento (1-31) <span class="required">*</span></label>
                    <input type="number" name="dia_vencimiento" id="dia_vencimiento" min="1" max="31"
                           value="{{ old('dia_vencimiento', $service->dia_vencimiento) }}" required>
                    @error('dia_vencimiento') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3">{{ old('descripcion', $service->descripcion) }}</textarea>
                    @error('descripcion') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label>
                        <input type="checkbox" name="activo" value="1"
                            {{ old('activo', $service->activo) ? 'checked' : '' }}>
                        Servicio activo
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
                <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
