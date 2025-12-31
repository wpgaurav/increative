{{--
  Breadcrumbs Component
  
  @param string $class Additional CSS classes
  @param bool $show_home Show home link (default: true)
  @param string $separator Separator character (default: /)
--}}

@props([
  'class' => '',
  'showHome' => true,
  'separator' => '/'
])

@php
  $items = [];
  $position = 1;
  
  // Home
  if ($showHome) {
    $items[] = [
      'name' => __('Home', 'sage'),
      'url' => home_url('/'),
      'icon' => true,
    ];
  }
  
  if (is_singular('post')) {
    // Category
    $categories = get_the_category();
    if (!empty($categories)) {
      $primary_cat = $categories[0];
      $items[] = [
        'name' => $primary_cat->name,
        'url' => get_category_link($primary_cat->term_id),
      ];
    }
    // Current post
    $items[] = [
      'name' => get_the_title(),
      'url' => null,
    ];
    
  } elseif (is_singular('page')) {
    // Parent pages
    $parent_id = wp_get_post_parent_id(get_the_ID());
    $parents = [];
    
    while ($parent_id) {
      $parents[] = [
        'name' => get_the_title($parent_id),
        'url' => get_permalink($parent_id),
      ];
      $parent_id = wp_get_post_parent_id($parent_id);
    }
    
    $items = array_merge($items, array_reverse($parents));
    
    // Current page
    $items[] = [
      'name' => get_the_title(),
      'url' => null,
    ];
    
  } elseif (is_category()) {
    // Parent categories
    $cat = get_queried_object();
    $parents = [];
    $parent_id = $cat->parent;
    
    while ($parent_id) {
      $parent = get_category($parent_id);
      $parents[] = [
        'name' => $parent->name,
        'url' => get_category_link($parent->term_id),
      ];
      $parent_id = $parent->parent;
    }
    
    $items = array_merge($items, array_reverse($parents));
    
    $items[] = [
      'name' => single_cat_title('', false),
      'url' => null,
    ];
    
  } elseif (is_tag()) {
    $items[] = [
      'name' => single_tag_title('', false),
      'url' => null,
    ];
    
  } elseif (is_author()) {
    $items[] = [
      'name' => __('Authors', 'sage'),
      'url' => null,
    ];
    $items[] = [
      'name' => get_the_author(),
      'url' => null,
    ];
    
  } elseif (is_search()) {
    $items[] = [
      'name' => sprintf(__('Search: %s', 'sage'), get_search_query()),
      'url' => null,
    ];
    
  } elseif (is_404()) {
    $items[] = [
      'name' => __('Page Not Found', 'sage'),
      'url' => null,
    ];
    
  } elseif (is_archive()) {
    if (is_post_type_archive()) {
      $items[] = [
        'name' => post_type_archive_title('', false),
        'url' => null,
      ];
    } elseif (is_date()) {
      if (is_day()) {
        $items[] = ['name' => get_the_date(), 'url' => null];
      } elseif (is_month()) {
        $items[] = ['name' => get_the_date('F Y'), 'url' => null];
      } elseif (is_year()) {
        $items[] = ['name' => get_the_date('Y'), 'url' => null];
      }
    }
  }
  
  // Filter to modify breadcrumbs
  $items = apply_filters('increative_breadcrumbs', $items);
@endphp

@if (count($items) > 1)
  <nav class="breadcrumbs {{ $class }}" aria-label="{{ __('Breadcrumb', 'sage') }}">
    <ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
      @foreach ($items as $index => $item)
        <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
          @if ($item['url'])
            @if (!empty($item['icon']))
              <a href="{{ $item['url'] }}" class="breadcrumbs__home" itemprop="item">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                  <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span class="sr-only" itemprop="name">{{ $item['name'] }}</span>
              </a>
            @else
              <a href="{{ $item['url'] }}" class="breadcrumbs__link" itemprop="item">
                <span itemprop="name">{{ $item['name'] }}</span>
              </a>
            @endif
            <meta itemprop="position" content="{{ $loop->iteration }}">
          @else
            <span class="breadcrumbs__current" itemprop="name">{{ Str::limit($item['name'], 50) }}</span>
            <meta itemprop="position" content="{{ $loop->iteration }}">
          @endif
          
          @if (!$loop->last)
            <span class="breadcrumbs__sep" aria-hidden="true">{{ $separator }}</span>
          @endif
        </li>
      @endforeach
    </ol>
  </nav>
@endif
