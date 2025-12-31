@props([
  'post' => null,
  'showImage' => true,
  'showExcerpt' => true,
])

@php
  $post = $post ?? get_post();
  $permalink = get_permalink($post);
  $title = get_the_title($post);
  $date = get_the_date('', $post);
  $categories = get_the_category($post->ID);
@endphp

<article {{ $attributes->merge(['class' => 'card']) }}>
  @if($showImage && has_post_thumbnail($post))
    <a href="{{ $permalink }}" class="block overflow-hidden">
      {!! get_the_post_thumbnail($post, 'medium_large', ['class' => 'card__image transition-transform duration-300 hover:scale-105']) !!}
    </a>
  @endif

  <div class="card__content">
    {{-- Meta --}}
    <div class="card__meta">
      <time datetime="{{ get_post_time('c', true, $post) }}">
        {{ $date }}
      </time>
      @if($categories)
        <span>•</span>
        <a href="{{ get_category_link($categories[0]) }}" class="hover:text-accent">
          {{ $categories[0]->name }}
        </a>
      @endif
    </div>

    {{-- Title --}}
    <h3 class="card__title">
      <a href="{{ $permalink }}">
        {!! $title !!}
      </a>
    </h3>

    {{-- Excerpt --}}
    @if($showExcerpt)
      <p class="card__excerpt">
        {!! wp_trim_words(get_the_excerpt($post), 20) !!}
      </p>
    @endif

    {{-- Slot for additional content --}}
    {{ $slot }}
  </div>
</article>
