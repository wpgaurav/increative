{{--
  Pagination Component
  
  @param WP_Query $query Optional custom query
  @param string $type 'numbered' | 'simple' | 'loadmore'
  @param int $range Number of pages to show around current
--}}

@props([
  'query' => null,
  'type' => 'numbered',
  'range' => 2
])

@php
  $query = $query ?: $GLOBALS['wp_query'];
  $total = $query->max_num_pages;
  $current = max(1, get_query_var('paged'));
  
  if ($total <= 1) {
    return;
  }
  
  $big = 999999999;
  $pages = paginate_links([
    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format' => '?paged=%#%',
    'current' => $current,
    'total' => $total,
    'type' => 'array',
    'prev_next' => false,
    'mid_size' => $range,
    'end_size' => 1,
  ]);
@endphp

@if ($type === 'numbered' && $pages)
  <nav class="pagination" aria-label="{{ __('Pagination', 'sage') }}" role="navigation">
    {{-- Previous --}}
    @if ($current > 1)
      <a href="{{ get_pagenum_link($current - 1) }}" class="pagination__prev" rel="prev">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
        <span class="sr-only">{{ __('Previous', 'sage') }}</span>
      </a>
    @else
      <span class="pagination__prev pagination__prev--disabled" aria-disabled="true">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
      </span>
    @endif

    {{-- Page Numbers --}}
    @foreach ($pages as $page)
      @if (strpos($page, 'current') !== false)
        @php preg_match('/>(\d+)</', $page, $matches) @endphp
        <span class="pagination__current" aria-current="page">{{ $matches[1] ?? $current }}</span>
      @elseif (strpos($page, 'dots') !== false)
        <span class="pagination__dots">…</span>
      @else
        @php 
          preg_match('/href=["\']([^"\']+)["\']/', $page, $href);
          preg_match('/>(\d+)</', $page, $num);
        @endphp
        <a href="{{ $href[1] ?? '#' }}" class="pagination__link">{{ $num[1] ?? '' }}</a>
      @endif
    @endforeach

    {{-- Next --}}
    @if ($current < $total)
      <a href="{{ get_pagenum_link($current + 1) }}" class="pagination__next" rel="next">
        <span class="sr-only">{{ __('Next', 'sage') }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>
    @else
      <span class="pagination__next pagination__next--disabled" aria-disabled="true">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </span>
    @endif
  </nav>

@elseif ($type === 'simple')
  <nav class="post-navigation" aria-label="{{ __('Posts navigation', 'sage') }}">
    @if ($current > 1)
      <a href="{{ get_pagenum_link($current - 1) }}" class="post-navigation__item post-navigation__item--prev" rel="prev">
        <span class="post-navigation__label">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
          {{ __('Previous', 'sage') }}
        </span>
        <span class="post-navigation__title">{{ __('Older Posts', 'sage') }}</span>
      </a>
    @else
      <div></div>
    @endif
    
    @if ($current < $total)
      <a href="{{ get_pagenum_link($current + 1) }}" class="post-navigation__item post-navigation__item--next" rel="next">
        <span class="post-navigation__label">
          {{ __('Next', 'sage') }}
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="post-navigation__title">{{ __('Newer Posts', 'sage') }}</span>
      </a>
    @endif
  </nav>

@elseif ($type === 'loadmore' && $current < $total)
  <div class="load-more">
    <button 
      class="load-more__btn" 
      data-page="{{ $current }}"
      data-max="{{ $total }}"
      data-url="{{ esc_url(admin_url('admin-ajax.php')) }}"
    >
      <span class="load-more__text">{{ __('Load More', 'sage') }}</span>
      <span class="load-more__spinner" style="display: none;"></span>
    </button>
  </div>
@endif
