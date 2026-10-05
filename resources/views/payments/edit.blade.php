@extends('layouts.app')

@section('title', 'Editar pago')

@section('page_title', '✏️ Editar pago')

@section('content')
    <div class="card">
        <form action="{{ route('pagos.update', $payment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="service_id">Servicio <span class="required">*</span></label>
                    <select name="service_id" id="service_id" required>
                        <option value="">— Selecciona un servicio —</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" data-costo="{{ $service->costo_mensual }}"
                                {{ old('service_id', $payment->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->nombre }} — Bs {{ number_format($service->costo_mensual, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="fecha_pago">Fecha del pago <span class="required">*</span></label>
                    <input type="date" name="fecha_pago" id="fecha_pago"
                           value="{{ old('fecha_pago', $payment->fecha_pago->format('Y-m-d')) }}" required>
                    @error('fecha_pago') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="monto_total">Monto total (Bs) <span class="required">*</span></label>
                    <input type="number" name="monto_total" id="monto_total" step="0.01" min="0"
                           value="{{ old('monto_total', $payment->monto_total) }}" required>
                    @error('monto_total') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="estado">Estado <span class="required">*</span></label>
                    <select name="estado" id="estado" required>
                        @foreach (\App\Models\Payment::ESTADOS as $valor => $etiqueta)
                            <option value="{{ $valor }}"
                                {{ old('estado', $payment->estado) === $valor ? 'selected' : '' }}>
                                {{ $etiqueta }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label for="notas">Notas</label>
                    <textarea name="notas" id="notas" rows="2">{{ old('notas', $payment->notas) }}</textarea>
                    @error('notas') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <h3 style="margin: 24px 0 12px;">👥 ¿Quiénes pagaron?</h3>
            <p style="color: var(--text-muted); margin-bottom: 14px; font-size: 13px;">
                Ajusta las personas y montos. Las filas se reemplazan al guardar.
            </p>

            <div id="participants-container">
                @php
                    $participantesOld = old('participantes', $payment->people->map(function ($p) {
                        return ['person_id' => $p->id, 'monto_aportado' => $p->pivot->monto_aportado];
                    })->all());
                @endphp

                @foreach ($participantesOld as $i => $participante)
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
                                   value="{{ $participante['monto_aportado'] }}">
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
                <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
                <a href="{{ route('pagos.show', $payment) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
