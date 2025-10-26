@if ($paginator->hasPages())
  <nav aria-label="Page navigation">
    <ul class="pagination">
      {{-- First Page Link --}}
      <li class="page-item first {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="@lang('pagination.first')"><i
            class="icon-base bx bx-chevrons-left icon-sm"></i></a>
      </li>

      {{-- Previous Page Link --}}
      <li class="page-item prev {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><i
            class="icon-base bx bx-chevron-left icon-sm"></i></a>
      </li>

      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
            @else
              <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      <li class="page-item next {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"><i
            class="icon-base bx bx-chevron-right icon-sm"></i></a>
      </li>

      {{-- Last Page Link --}}
      <li class="page-item last {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="@lang('pagination.last')"><i
            class="icon-base bx bx-chevrons-right icon-sm"></i></a>
      </li>
    </ul>
  </nav>
@endif
