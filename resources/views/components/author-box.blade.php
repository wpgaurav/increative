{{--
  Author Box Component
  
  @param WP_User|int $author Author object or ID
  @param string $class Additional CSS classes
  @param bool $compact Show compact version
--}}

@props([
  'author' => null,
  'class' => '',
  'compact' => false
])

@php
  if (!$author) {
    $author = get_the_author_meta('ID');
  }
  
  if (is_numeric($author)) {
    $author_id = $author;
  } else {
    $author_id = $author->ID ?? get_the_author_meta('ID');
  }
  
  $name = get_the_author_meta('display_name', $author_id);
  $bio = get_the_author_meta('description', $author_id);
  $url = get_author_posts_url($author_id);
  $website = get_the_author_meta('user_url', $author_id);
  $avatar = get_avatar_url($author_id, ['size' => 200]);
  
  // Social links
  $social = [
    'twitter' => [
      'url' => get_the_author_meta('twitter', $author_id),
      'icon' => '<path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>',
      'label' => 'Twitter',
    ],
    'linkedin' => [
      'url' => get_the_author_meta('linkedin', $author_id),
      'icon' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
      'label' => 'LinkedIn',
    ],
    'github' => [
      'url' => get_the_author_meta('github', $author_id),
      'icon' => '<path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>',
      'label' => 'GitHub',
    ],
  ];
  
  // Author role (if set in user meta)
  $role = get_the_author_meta('role_title', $author_id) ?: '';
  
  // Post count
  $post_count = count_user_posts($author_id, 'post', true);
@endphp

<div class="author-box @if($compact) author-box--compact @endif {{ $class }}">
  <div class="author-box__avatar">
    <img 
      src="{{ $avatar }}" 
      alt="{{ $name }}"
      width="100"
      height="100"
      loading="lazy"
    >
  </div>
  
  <div class="author-box__content">
    @if (!$compact)
      <span class="author-box__label">{{ __('Written by', 'sage') }}</span>
    @endif
    
    <h3 class="author-box__name">
      <a href="{{ $url }}">{{ $name }}</a>
    </h3>
    
    @if (!$compact && $role)
      <span class="author-box__role">{{ $role }}</span>
    @endif
    
    @if (!$compact && $bio)
      <p class="author-box__bio">{{ $bio }}</p>
    @endif
    
    @if (!$compact)
      <div class="author-box__meta">
        <span class="author-box__meta-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/>
          </svg>
          {{ sprintf(_n('%d article', '%d articles', $post_count, 'sage'), $post_count) }}
        </span>
        
        @if ($website)
          <a href="{{ $website }}" class="author-box__meta-item" target="_blank" rel="noopener">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <line x1="2" y1="12" x2="22" y2="12"/>
              <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
            {{ __('Website', 'sage') }}
          </a>
        @endif
      </div>
      
      {{-- Social Links --}}
      @php $has_social = false; @endphp
      @foreach ($social as $key => $info)
        @if (!empty($info['url']))
          @php $has_social = true; @endphp
          @break
        @endif
      @endforeach
      
      @if ($has_social)
        <div class="author-box__social">
          @foreach ($social as $key => $info)
            @if (!empty($info['url']))
              <a 
                href="{{ $info['url'] }}" 
                class="author-box__social-link" 
                target="_blank" 
                rel="noopener"
                aria-label="{{ $info['label'] }}"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $info['icon'] !!}</svg>
              </a>
            @endif
          @endforeach
        </div>
      @endif
    @endif
  </div>
</div>
