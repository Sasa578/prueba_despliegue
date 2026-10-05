@if ($paginator->hasPages())
    <nav class="pagination">
        {{-- Enlace anterior --}}
        @if ($paginator->onFirstPage())
            <span class="page-item disabled">← Anterior</span>
        @else
            <a class="page-item" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Anterior</a>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-item disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-item active">{{ $page }}</span>
                    @else
                        <a class="page-item" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Enlace siguiente --}}
        @if ($paginator->hasMorePages())
            <a class="page-item" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente →</a>
        @else
            <span class="page-item disabled">Siguiente →</span>
        @endif
    </nav>
@endif
