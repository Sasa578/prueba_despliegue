@extends('layouts.app')

@section('title', 'Nuevo servicio')

@section('page_title', '🧾 Nuevo servicio')

@section('content')
    <div class="card">
        <form action="{{ route('servicios.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                           placeholder="Ej: Luz, Agua, Netflix, Spotify Familiar..." required>
                    @error('nombre') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría <span class="required">*</span></label>
                    <select name="categoria" id="categoria" required>
                        @foreach (\App\Models\Service::CATEGORIAS as $valor => $etiqueta)
                            <option value="{{ $valor }}" {{ old('categoria') === $valor ? 'selected' : '' }}>
                                {{ $etiqueta }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="costo_mensual">Costo mensual (Bs) <span class="required">*</span></label>
                    <input type="number" name="costo_mensual" id="costo_mensual" step="0.01" min="0"
                           value="{{ old('costo_mensual', '0.00') }}" placeholder="Ej: 250.00" required>
                    @error('costo_mensual') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="dia_vencimiento">Día de vencimiento (1-31) <span class="required">*</span></label>
                    <input type="number" name="dia_vencimiento" id="dia_vencimiento" min="1" max="31"
                           value="{{ old('dia_vencimiento', 1) }}" required>
                    @error('dia_vencimiento') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                              placeholder="Ej: Plan familiar entre 4 personas">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label>
                        <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                        Servicio activo
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar servicio</button>
                <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
