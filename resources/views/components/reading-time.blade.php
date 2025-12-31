{{--
  Reading Time Component
  
  @param WP_Post|int $post Post object or ID (defaults to current post)
  @param int $wpm Words per minute (default: 200)
--}}

@props([
  'post' => null,
  'wpm' => 200
])

@php
  if (!$post) {
    $post = get_post();
  } elseif (is_numeric($post)) {
    $post = get_post($post);
  }
  
  if (!$post) return;
  
  $content = $post->post_content;
  $word_count = str_word_count(strip_tags($content));
  $reading_time = ceil($word_count / $wpm);
  
  // Minimum 1 minute
  $reading_time = max(1, $reading_time);
@endphp

<span class="reading-time">
  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <circle cx="12" cy="12" r="10"/>
    <polyline points="12 6 12 12 16 14"/>
  </svg>
  <span class="reading-time__value">
    {{ sprintf(_n('%d min read', '%d min read', $reading_time, 'sage'), $reading_time) }}
  </span>
</span>
