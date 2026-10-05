@extends('layouts.app')

@section('title', 'Servicios')

@section('page_title', '🧾 Servicios')

@section('actions')
    <a href="{{ route('servicios.create') }}" class="btn btn-primary">＋ Nuevo servicio</a>
@endsection

@section('content')
    <div class="card">
        @if ($services->isEmpty())
            <div class="empty-state">
                <span class="empty-icon">🧾</span>
                No hay servicios registrados todavía.
                <br><br>
                <a href="{{ route('servicios.create') }}" class="btn btn-primary btn-sm">＋ Crear el primero</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Costo mensual</th>
                            <th>Vence (día)</th>
                            <th>Estado</th>
                            <th>Pagos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td><strong>{{ $service->nombre }}</strong></td>
                                <td>
                                    <span class="badge badge-categoria">
                                        {{ \App\Models\Service::CATEGORIAS[$service->categoria] ?? 'Otros' }}
                                    </span>
                                </td>
                                <td>Bs {{ number_format($service->costo_mensual, 2) }}</td>
                                <td>Día {{ $service->dia_vencimiento }}</td>
                                <td>
                                    @if ($service->activo)
                                        <span class="badge badge-pagado">Activo</span>
                                    @else
                                        <span class="badge badge-inactivo">Inactivo</span>
                                    @endif
                                </td>
                                <td>{{ $service->payments_count }}</td>
                                <td class="table-actions">
                                    <a href="{{ route('servicios.edit', $service) }}" class="btn btn-secondary btn-sm">✏️ Editar</a>
                                    <form action="{{ route('servicios.destroy', $service) }}" method="POST"
                                          style="display:inline"
                                          onsubmit="return confirm('¿Eliminar el servicio \'{{ addslashes($service->nombre) }}\' y todos sus pagos?');">
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
        @endif
    </div>
@endsection
