@extends('layouts.app')

@section('title', 'Personas')

@section('page_title', '👥 Personas')

@section('actions')
    <a href="{{ route('personas.create') }}" class="btn btn-primary">＋ Nueva persona</a>
@endsection

@section('content')
    <div class="card">
        <p style="color: var(--text-muted); margin-bottom: 16px;">
            Las personas registradas aquí pueden compartir el pago de un servicio
            (por ejemplo, un plan familiar de Spotify).
        </p>

        @if ($people->isEmpty())
            <div class="empty-state">
                <span class="empty-icon">👥</span>
                No hay personas registradas todavía.
                <br><br>
                <a href="{{ route('personas.create') }}" class="btn btn-primary btn-sm">＋ Registrar la primera</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Pagos en los que participa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($people as $person)
                            <tr>
                                <td><strong>{{ $person->nombre }}</strong></td>
                                <td>{{ $person->email ?: '—' }}</td>
                                <td>{{ $person->telefono ?: '—' }}</td>
                                <td>{{ $person->payments_count }}</td>
                                <td>
                                    <a href="{{ route('personas.edit', $person) }}" class="btn btn-secondary btn-sm">✏️ Editar</a>
                                    <form action="{{ route('personas.destroy', $person) }}" method="POST"
                                          style="display:inline"
                                          onsubmit="return confirm('¿Eliminar a {{ addslashes($person->nombre) }}?');">
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
