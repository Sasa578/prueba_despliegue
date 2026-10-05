@extends('layouts.app')

@section('title', 'Panel principal')

@section('page_title', '📊 PANEL CENTRAL')

@section('actions')
    <a href="{{ route('pagos.create') }}" class="btn btn-primary">＋ Registrar pago</a>
@endsection

@section('content')
    @php
        $totalServicios = $vencimientos->count();
        $pendientes = $vencimientos->where('estado', 'pendiente')->count();
        $vencidos = $vencimientos->where('estado', 'vencido')->count();
        $pagados = $vencimientos->where('estado', 'pagado')->count();
    @endphp

    {{-- Resumen --}}
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon">🧾</span>
            <div class="stat-label">Servicios activos</div>
            <div class="stat-value">{{ $totalServicios }}</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">✅</span>
            <div class="stat-label">Pagados este mes</div>
            <div class="stat-value">{{ $pagados }}</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">⏳</span>
            <div class="stat-label">Pendientes</div>
            <div class="stat-value">{{ $pendientes }}</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">🚨</span>
            <div class="stat-label">Vencidos</div>
            <div class="stat-value">{{ $vencidos }}</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">💰</span>
            <div class="stat-label">Total pagado este mes</div>
            <div class="stat-value">Bs {{ number_format($totalPagadoMes, 2) }}</div>
        </div>
    </div>

    {{-- Próximos vencimientos --}}
    <div class="card">
        <h3>📅 Estado de vencimientos (este mes)</h3>
        @if ($vencimientos->isEmpty())
            <div class="empty-state">
                <span class="empty-icon">🗓️</span>
                No hay servicios activos todavía.
                <br><br>
                <a href="{{ route('servicios.create') }}" class="btn btn-primary btn-sm">＋ Crear servicio</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Categoría</th>
                            <th>Costo mensual</th>
                            <th>Vence el</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vencimientos as $item)
                            <tr>
                                <td><strong>{{ $item->service->nombre }}</strong></td>
                                <td><span class="badge badge-categoria">{{ $item->service->categoria_label ?? 'Otros' }}</span></td>
                                <td>Bs {{ number_format($item->service->costo_mensual, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->fecha_vencimiento)->translatedFormat('d \d\e M, Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->estado }}">
                                        {{ ucfirst($item->estado) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->estado !== 'pagado')
                                        <a href="{{ route('pagos.create', ['service_id' => $item->service->id]) }}"
                                           class="btn btn-primary btn-sm">Registrar pago</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Pagos del mes --}}
    <div class="card">
        <h3>💳 Pagos registrados este mes</h3>
        @if ($pagosDelMes->isEmpty())
            <div class="empty-state">
                <span class="empty-icon">💤</span>
                Aún no hay pagos registrados este mes.
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Pagado por</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagosDelMes as $pago)
                            <tr>
                                <td><strong>{{ $pago->service->nombre }}</strong></td>
                                <td>{{ $pago->fecha_pago->translatedFormat('d/m/Y') }}</td>
                                <td>Bs {{ number_format($pago->monto_total, 2) }}</td>
                                <td><span class="badge badge-{{ $pago->estado }}">{{ ucfirst($pago->estado) }}</span></td>
                                <td>{{ $pago->people->pluck('nombre')->implode(', ') ?: '—' }}</td>
                                <td><a href="{{ route('pagos.show', $pago) }}" class="btn btn-secondary btn-sm">Ver</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
