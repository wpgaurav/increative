{{--
  Post Meta Component
  
  @param WP_Post $post Post object
  @param array $show Which meta items to show
--}}

@props([
  'post' => null,
  'show' => ['date', 'author', 'category', 'reading_time']
])

@php
  $post = $post ?: get_post();
  if (!$post) return;
@endphp

<div class="post-meta">
  @if (in_array('date', $show))
    <time class="post-meta__date" datetime="{{ get_the_date('c', $post) }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
      {{ get_the_date('', $post) }}
    </time>
  @endif
  
  @if (in_array('author', $show))
    <span class="post-meta__author">
      <a href="{{ get_author_posts_url($post->post_author) }}" class="author-byline">
        <img 
          src="{{ get_avatar_url($post->post_author, ['size' => 48]) }}" 
          alt="{{ get_the_author_meta('display_name', $post->post_author) }}"
          class="author-byline__avatar"
          width="24"
          height="24"
          loading="lazy"
        >
        <span class="author-byline__name">{{ get_the_author_meta('display_name', $post->post_author) }}</span>
      </a>
    </span>
  @endif
  
  @if (in_array('category', $show))
    @php $categories = get_the_category($post->ID); @endphp
    @if (!empty($categories))
      <span class="post-meta__category">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 3h6l2 3h10v13H3z"/>
        </svg>
        <a href="{{ get_category_link($categories[0]->term_id) }}">{{ $categories[0]->name }}</a>
      </span>
    @endif
  @endif
  
  @if (in_array('reading_time', $show))
    <x-reading-time :post="$post" />
  @endif
  
  @if (in_array('comments', $show) && comments_open($post->ID))
    @php $comment_count = get_comments_number($post->ID); @endphp
    <a href="{{ get_comments_link($post->ID) }}" class="post-meta__comments">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
      </svg>
      {{ sprintf(_n('%d comment', '%d comments', $comment_count, 'sage'), $comment_count) }}
    </a>
  @endif
</div>
