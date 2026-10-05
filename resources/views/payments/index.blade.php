@extends('layouts.app')

@section('title', 'Pagos')

@section('page_title', '💳 Pagos')

@section('actions')
    <a href="{{ route('pagos.create') }}" class="btn btn-primary">＋ Registrar pago</a>
@endsection

@section('content')
    <div class="card">
        {{-- Filtros --}}
        <form method="GET" action="{{ route('pagos.index') }}" class="filters" style="margin-bottom: 20px;">
            <select name="estado" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                @foreach (\App\Models\Payment::ESTADOS as $valor => $etiqueta)
                    <option value="{{ $valor }}" {{ request('estado') === $valor ? 'selected' : '' }}>
                        {{ $etiqueta }}
                    </option>
                @endforeach
            </select>

            <select name="service_id" onchange="this.form.submit()">
                <option value="">Todos los servicios</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->nombre }}
                    </option>
                @endforeach
            </select>

            @if (request()->filled('estado') || request()->filled('service_id'))
                <a href="{{ route('pagos.index') }}" class="btn btn-secondary btn-sm">✕ Limpiar filtros</a>
            @endif
        </form>

        @if ($payments->isEmpty())
            <div class="empty-state">
                <span class="empty-icon">💳</span>
                No hay pagos registrados con esos filtros.
                <br><br>
                <a href="{{ route('pagos.create') }}" class="btn btn-primary btn-sm">＋ Registrar un pago</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Monto total</th>
                            <th>Estado</th>
                            <th>Pagado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->service->nombre }}</strong></td>
                                <td>{{ $payment->fecha_pago->translatedFormat('d/m/Y') }}</td>
                                <td>Bs {{ number_format($payment->monto_total, 2) }}</td>
                                <td><span class="badge badge-{{ $payment->estado }}">{{ ucfirst($payment->estado) }}</span></td>
                                <td>
                                    @if ($payment->people->isNotEmpty())
                                        {{ $payment->people->pluck('nombre')->implode(', ') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pagos.show', $payment) }}" class="btn btn-secondary btn-sm">👁️ Ver</a>
                                    <a href="{{ route('pagos.edit', $payment) }}" class="btn btn-secondary btn-sm">✏️ Editar</a>
                                    <form action="{{ route('pagos.destroy', $payment) }}" method="POST"
                                          style="display:inline"
                                          onsubmit="return confirm('¿Eliminar este pago?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection
