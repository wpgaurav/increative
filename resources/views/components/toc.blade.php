{{--
  Table of Contents Component
  
  @param string $content Post content to extract headings from
  @param string $class Additional CSS classes
  @param bool $sticky Make TOC sticky in sidebar
  @param int $minHeadings Minimum headings required to show TOC
--}}

@props([
  'content' => '',
  'class' => '',
  'sticky' => false,
  'minHeadings' => 3
])

@php
  // Extract headings from content
  preg_match_all('/<h([2-4])[^>]*id=["\']([^"\']+)["\'][^>]*>(.+?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER);
  
  // If no IDs, try to match headings without IDs
  if (empty($matches)) {
    preg_match_all('/<h([2-4])[^>]*>(.+?)<\/h\1>/i', $content, $raw_matches, PREG_SET_ORDER);
    $matches = [];
    
    foreach ($raw_matches as $index => $match) {
      $text = wp_strip_all_tags($match[2]);
      $id = sanitize_title($text) . '-' . $index;
      $matches[] = [
        0 => $match[0],
        1 => $match[1],
        2 => $id,
        3 => $text,
      ];
    }
  }
  
  // Check minimum headings
  if (count($matches) < $minHeadings) {
    return;
  }
  
  $headings = [];
  foreach ($matches as $match) {
    $headings[] = [
      'level' => (int) $match[1],
      'id' => $match[2],
      'text' => wp_strip_all_tags($match[3] ?? $match[2]),
    ];
  }
@endphp

@if (!empty($headings))
  <nav class="toc {{ $class }} @if($sticky) toc--sticky @endif" aria-label="{{ __('Table of Contents', 'sage') }}">
    <div class="toc__header">
      <h2 class="toc__title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="8" y1="6" x2="21" y2="6"/>
          <line x1="8" y1="12" x2="21" y2="12"/>
          <line x1="8" y1="18" x2="21" y2="18"/>
          <line x1="3" y1="6" x2="3.01" y2="6"/>
          <line x1="3" y1="12" x2="3.01" y2="12"/>
          <line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>
        {{ __('Table of Contents', 'sage') }}
      </h2>
      <button class="toc__toggle" aria-expanded="true" aria-controls="toc-list">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="18 15 12 9 6 15"/>
        </svg>
        <span class="sr-only">{{ __('Toggle table of contents', 'sage') }}</span>
      </button>
    </div>
    
    <ol class="toc__list" id="toc-list">
      @foreach ($headings as $heading)
        <li class="toc__item toc__item--h{{ $heading['level'] }}">
          <a href="#{{ $heading['id'] }}" class="toc__link" data-toc-target="{{ $heading['id'] }}">
            {{ $heading['text'] }}
          </a>
        </li>
      @endforeach
    </ol>
    
    <div class="toc__progress">
      <div class="toc__progress-bar"></div>
    </div>
  </nav>
  
  {{-- Mobile FAB (Floating Action Button) --}}
  <button class="toc-fab" aria-label="{{ __('Open table of contents', 'sage') }}" data-toc-trigger>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <line x1="8" y1="6" x2="21" y2="6"/>
      <line x1="8" y1="12" x2="21" y2="12"/>
      <line x1="8" y1="18" x2="21" y2="18"/>
      <line x1="3" y1="6" x2="3.01" y2="6"/>
      <line x1="3" y1="12" x2="3.01" y2="12"/>
      <line x1="3" y1="18" x2="3.01" y2="18"/>
    </svg>
  </button>
@endif
