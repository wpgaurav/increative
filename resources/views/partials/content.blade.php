{{-- Blog Post Card --}}
<article @php(post_class('card'))>
  @if (has_post_thumbnail())
    <a href="{{ get_permalink() }}" class="block overflow-hidden">
      {!! get_the_post_thumbnail(null, 'medium_large', ['class' => 'card__image transition-transform duration-300 hover:scale-105']) !!}
    </a>
  @endif

  <div class="card__content">
    {{-- Meta --}}
    <div class="card__meta">
      <time datetime="{{ get_post_time('c', true) }}">
        {{ get_the_date() }}
      </time>
      @if ($categories = get_the_category())
        <span>•</span>
        <a href="{{ get_category_link($categories[0]) }}" class="hover:text-accent">
          {{ $categories[0]->name }}
        </a>
      @endif
    </div>

    {{-- Title --}}
    <h2 class="card__title">
      <a href="{{ get_permalink() }}">
        {!! $title !!}
      </a>
    </h2>

    {{-- Excerpt --}}
    <p class="card__excerpt">
      {!! wp_trim_words(get_the_excerpt(), 20) !!}
    </p>

    {{-- Read More --}}
    <a href="{{ get_permalink() }}" class="inline-flex items-center gap-1 text-sm font-medium text-accent hover:text-accent-hover mt-2">
      {{ __('Read more', 'sage') }}
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
      </svg>
    </a>
  </div>
</article>
