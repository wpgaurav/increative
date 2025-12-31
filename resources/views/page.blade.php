@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <article @php(post_class())>
      <div class="container container-narrow">
        {{-- Page Header --}}
        <header class="mb-8 pb-8 border-b border-border">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">
            {!! $title !!}
          </h1>
          @if (has_excerpt())
            <p class="text-lg text-muted">
              {!! get_the_excerpt() !!}
            </p>
          @endif
        </header>

        {{-- Featured Image --}}
        @if (has_post_thumbnail())
          <figure class="mb-8 -mx-4 md:mx-0">
            {!! get_the_post_thumbnail(null, 'large', ['class' => 'w-full rounded-xl']) !!}
          </figure>
        @endif

        {{-- Page Content --}}
        <div class="post-content">
          @php(the_content())
        </div>

        {{-- Page Link Pages --}}
        {!! wp_link_pages([
          'before' => '<nav class="page-links mt-8 pt-8 border-t border-border">' . __('Pages:', 'sage'),
          'after' => '</nav>',
          'echo' => false,
        ]) !!}
      </div>
    </article>

    {{-- Comments (if enabled for pages) --}}
    @if (comments_open() || get_comments_number())
      <section class="container container-narrow mt-16">
        @php(comments_template())
      </section>
    @endif
  @endwhile
@endsection
