@extends('layouts.app')

@section('title', 'Detalle del pago')

@section('page_title', '👁️ Detalle del pago')

@section('actions')
    <a href="{{ route('pagos.edit', $payment) }}" class="btn btn-secondary">✏️ Editar</a>
    <a href="{{ route('pagos.index') }}" class="btn btn-secondary">← Volver</a>
@endsection

@section('content')
    <div class="card">
        <h3>Información del pago</h3>
        <ul class="detail-list">
            <li>
                <span class="detail-label">Servicio</span>
                <span class="detail-value">{{ $payment->service->nombre }}</span>
            </li>
            <li>
                <span class="detail-label">Categoría</span>
                <span class="detail-value">
                    {{ \App\Models\Service::CATEGORIAS[$payment->service->categoria] ?? 'Otros' }}
                </span>
            </li>
            <li>
                <span class="detail-label">Fecha del pago</span>
                <span class="detail-value">{{ $payment->fecha_pago->translatedFormat('d \d\e F \d\e Y') }}</span>
            </li>
            <li>
                <span class="detail-label">Monto total</span>
                <span class="detail-value">Bs {{ number_format($payment->monto_total, 2) }}</span>
            </li>
            <li>
                <span class="detail-label">Estado</span>
                <span class="detail-value">
                    <span class="badge badge-{{ $payment->estado }}">{{ ucfirst($payment->estado) }}</span>
                </span>
            </li>
            @if ($payment->notas)
                <li>
                    <span class="detail-label">Notas</span>
                    <span class="detail-value">{{ $payment->notas }}</span>
                </li>
            @endif
        </ul>
    </div>

    <div class="card">
        <h3>👥 Personas que pagaron</h3>

        @if ($payment->people->isEmpty())
            <div class="empty-state">
                <span class="empty-icon">👤</span>
                No se registraron personas para este pago.
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Persona</th>
                            <th>Monto aportado</th>
                            <th>% del total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payment->people as $person)
                            <tr>
                                <td><strong>{{ $person->nombre }}</strong></td>
                                <td>Bs {{ number_format($person->pivot->monto_aportado, 2) }}</td>
                                <td>
                                    @if ($payment->monto_total > 0)
                                        {{ number_format(($person->pivot->monto_aportado / $payment->monto_total) * 100, 1) }}%
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total aportado</th>
                            <th>Bs {{ number_format($payment->totalAportado(), 2) }}</th>
                            <th>
                                @if ($payment->monto_total > 0)
                                    {{ number_format(($payment->totalAportado() / $payment->monto_total) * 100, 1) }}%
                                @else
                                    —
                                @endif
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
@endsection
