@extends('layouts.app')

@section('title', 'Registrar pago')

@section('page_title', '💳 Registrar pago')

@section('content')
    <div class="card">
        <form action="{{ route('pagos.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="service_id">Servicio <span class="required">*</span></label>
                    <select name="service_id" id="service_id" required>
                        <option value="">— Selecciona un servicio —</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" data-costo="{{ $service->costo_mensual }}"
                                {{ old('service_id', request('service_id')) == $service->id ? 'selected' : '' }}>
                                {{ $service->nombre }} — Bs {{ number_format($service->costo_mensual, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="fecha_pago">Fecha del pago <span class="required">*</span></label>
                    <input type="date" name="fecha_pago" id="fecha_pago"
                           value="{{ old('fecha_pago', now()->format('Y-m-d')) }}" required>
                    @error('fecha_pago') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="monto_total">Monto total (Bs) <span class="required">*</span></label>
                    <input type="number" name="monto_total" id="monto_total" step="0.01" min="0"
                           value="{{ old('monto_total') }}" placeholder="Se autocompleta con el costo del servicio" required>
                    @error('monto_total') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="estado">Estado <span class="required">*</span></label>
                    <select name="estado" id="estado" required>
                        @foreach (\App\Models\Payment::ESTADOS as $valor => $etiqueta)
                            <option value="{{ $valor }}" {{ old('estado', 'pagado') === $valor ? 'selected' : '' }}>
                                {{ $etiqueta }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label for="notas">Notas</label>
                    <textarea name="notas" id="notas" rows="2"
                              placeholder="Ej: Aporte dividido entre los 4 miembros del plan">{{ old('notas') }}</textarea>
                    @error('notas') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Participantes (quiénes pagaron y cuánto aportó cada uno) --}}
            <h3 style="margin: 24px 0 12px;">👥 ¿Quiénes pagaron?</h3>
            <p style="color: var(--text-muted); margin-bottom: 14px; font-size: 13px;">
                Si un servicio lo pagan varias personas (por ejemplo, un plan familiar),
                agrega una fila por cada persona con el monto que aportó.
            </p>

            <div id="participants-container">
                @foreach (old('participantes', []) as $i => $participante)
                    @if (!empty($participante['person_id']))
                        <div class="participant-row">
                            <select name="participantes[{{ $i }}][person_id]" required>
                                <option value="">— Selecciona una persona —</option>
                                @foreach ($people as $person)
                                    <option value="{{ $person->id }}"
                                        {{ $participante['person_id'] == $person->id ? 'selected' : '' }}>
                                        {{ $person->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="participantes[{{ $i }}][monto_aportado]"
                                   step="0.01" min="0" placeholder="Monto (Bs)" required
                                   value="{{ $participante['monto_aportado'] ?? '' }}">
                            <button type="button" class="btn-remove-row" title="Quitar">✕</button>
                        </div>
                    @endif
                @endforeach
            </div>

            @error('participantes.*.person_id') <span class="field-error">{{ $message }}</span> @enderror
            @error('participantes.*.monto_aportado') <span class="field-error">{{ $message }}</span> @enderror

            <button type="button" id="add-participant" class="btn btn-secondary" style="margin-top: 6px;">
                ＋ Agregar persona
            </button>

            <template id="participant-template">
                <div class="participant-row">
                    <select name="participantes[0][person_id]" required>
                        <option value="">— Selecciona una persona —</option>
                        @foreach ($people as $person)
                            <option value="{{ $person->id }}">{{ $person->nombre }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="participantes[0][monto_aportado]" step="0.01" min="0"
                           placeholder="Monto (Bs)" required>
                    <button type="button" class="btn-remove-row" title="Quitar">✕</button>
                </div>
            </template>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar pago</button>
                <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
