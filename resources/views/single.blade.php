@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <article @php(post_class('single-post'))>
      <div class="container container-narrow">
        {{-- Post Header --}}
        <header class="single-post__header">
          {{-- Meta --}}
          <div class="single-post__meta">
            <time datetime="{{ get_post_time('c', true) }}">
              {{ get_the_date() }}
            </time>
            @if ($categories = get_the_category())
              <span>•</span>
              @foreach ($categories as $category)
                <a href="{{ get_category_link($category) }}" class="hover:text-accent">
                  {{ $category->name }}
                </a>
                @if (!$loop->last), @endif
              @endforeach
            @endif
            <span>•</span>
            <span>{{ reading_time() }} {{ __('min read', 'sage') }}</span>
          </div>

          {{-- Title --}}
          <h1 class="single-post__title">
            {!! $title !!}
          </h1>

          {{-- Excerpt/Subtitle --}}
          @if (has_excerpt())
            <p class="single-post__excerpt">
              {!! get_the_excerpt() !!}
            </p>
          @endif

          {{-- Author --}}
          <div class="flex items-center gap-3 mt-6">
            {!! get_avatar(get_the_author_meta('email'), 48, '', '', ['class' => 'rounded-full']) !!}
            <div>
              <div class="font-semibold">{{ get_the_author() }}</div>
              <div class="text-sm text-muted">{{ get_the_author_meta('description') ?: __('Author', 'sage') }}</div>
            </div>
          </div>
        </header>

        {{-- Featured Image --}}
        @if (has_post_thumbnail())
          <figure class="my-8 -mx-4 md:mx-0">
            {!! get_the_post_thumbnail(null, 'large', ['class' => 'w-full rounded-xl']) !!}
            @if ($caption = get_the_post_thumbnail_caption())
              <figcaption class="mt-2 text-center text-sm text-muted">{{ $caption }}</figcaption>
            @endif
          </figure>
        @endif

        {{-- Post Content --}}
        <div class="post-content">
          @php(the_content())
        </div>

        {{-- Tags --}}
        @if ($tags = get_the_tags())
          <div class="flex flex-wrap gap-2 mt-8 pt-8 border-t border-border">
            @foreach ($tags as $tag)
              <a href="{{ get_tag_link($tag) }}" class="px-3 py-1 text-sm bg-bg-secondary rounded-full hover:bg-bg-tertiary transition-colors">
                #{{ $tag->name }}
              </a>
            @endforeach
          </div>
        @endif

        {{-- Post Navigation --}}
        <nav class="grid md:grid-cols-2 gap-4 mt-12 pt-8 border-t border-border">
          @php
            $prev = get_previous_post();
            $next = get_next_post();
          @endphp
          
          @if ($prev)
            <a href="{{ get_permalink($prev) }}" class="group p-4 border border-border rounded-xl hover:border-accent transition-colors">
              <span class="text-sm text-muted">{{ __('Previous', 'sage') }}</span>
              <span class="block font-semibold group-hover:text-accent transition-colors">{{ get_the_title($prev) }}</span>
            </a>
          @else
            <div></div>
          @endif
          
          @if ($next)
            <a href="{{ get_permalink($next) }}" class="group p-4 border border-border rounded-xl hover:border-accent transition-colors text-right">
              <span class="text-sm text-muted">{{ __('Next', 'sage') }}</span>
              <span class="block font-semibold group-hover:text-accent transition-colors">{{ get_the_title($next) }}</span>
            </a>
          @endif
        </nav>

        {{-- Author Box --}}
        <div class="mt-12 p-6 bg-bg-secondary rounded-2xl">
          <div class="flex gap-4">
            {!! get_avatar(get_the_author_meta('email'), 80, '', '', ['class' => 'rounded-full shrink-0']) !!}
            <div>
              <h3 class="text-lg font-semibold mb-1">{{ get_the_author() }}</h3>
              <p class="text-muted mb-3">{{ get_the_author_meta('description') }}</p>
              @if ($website = get_the_author_meta('url'))
                <a href="{{ $website }}" class="text-sm text-accent hover:text-accent-hover" target="_blank" rel="noopener">
                  {{ __('Visit website', 'sage') }} →
                </a>
              @endif
            </div>
          </div>
        </div>

        {{-- Related Posts --}}
        @php
          $related = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => [get_the_ID()],
            'category__in' => wp_get_post_categories(get_the_ID()),
          ]);
        @endphp
        
        @if ($related->have_posts())
          <section class="mt-16">
            <h2 class="text-2xl font-bold mb-6">{{ __('Related Posts', 'sage') }}</h2>
            <div class="posts-grid">
              @while ($related->have_posts()) @php($related->the_post())
                @include('partials.content')
              @endwhile
              @php(wp_reset_postdata())
            </div>
          </section>
        @endif

        {{-- Comments --}}
        @if (comments_open() || get_comments_number())
          <section class="mt-16">
            @php(comments_template())
          </section>
        @endif
      </div>
    </article>
  @endwhile
@endsection
