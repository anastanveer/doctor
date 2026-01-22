@if ($paginator->hasPages())
  <nav class="admin-pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
    <div class="admin-pagination__summary">
      @if ($paginator->firstItem())
        Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong>
      @else
        Showing <strong>{{ $paginator->count() }}</strong> of <strong>{{ $paginator->total() }}</strong>
      @endif
    </div>

    <div class="admin-pagination__list">
      @if ($paginator->onFirstPage())
        <span class="admin-pagination__item is-disabled" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
      @else
        <a class="admin-pagination__item" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      @endif

      @foreach ($elements as $element)
        @if (is_string($element))
          <span class="admin-pagination__item is-disabled">{{ $element }}</span>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="admin-pagination__item is-active">{{ $page }}</span>
            @else
              <a class="admin-pagination__item" href="{{ $url }}">{{ $page }}</a>
            @endif
          @endforeach
        @endif
      @endforeach

      @if ($paginator->hasMorePages())
        <a class="admin-pagination__item" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      @else
        <span class="admin-pagination__item is-disabled" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
      @endif
    </div>
  </nav>
@endif
