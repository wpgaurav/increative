@extends('layouts.app')

@section('content')
  <div class="container container-narrow">
    <div class="text-center py-16">
      {{-- 404 Illustration --}}
      <div class="text-9xl font-bold text-bg-tertiary mb-8">404</div>
      
      <h1 class="text-3xl md:text-4xl font-bold mb-4">
        {{ __('Page not found', 'sage') }}
      </h1>
      
      <p class="text-lg text-muted mb-8 max-w-md mx-auto">
        {{ __('Sorry, the page you\'re looking for doesn\'t exist or has been moved.', 'sage') }}
      </p>

      <div class="flex flex-wrap justify-center gap-4 mb-12">
        <a href="{{ home_url('/') }}" class="btn btn-primary">
          {{ __('Go home', 'sage') }}
        </a>
        <a href="{{ home_url('/contact') }}" class="btn btn-secondary">
          {{ __('Contact us', 'sage') }}
        </a>
      </div>

      {{-- Search Form --}}
      <div class="max-w-md mx-auto">
        <p class="text-sm text-muted mb-4">{{ __('Or try searching for what you need:', 'sage') }}</p>
        {!! get_search_form(false) !!}
      </div>

      {{-- Popular Posts --}}
      @php
        $popular_posts = new WP_Query([
          'post_type' => 'post',
          'posts_per_page' => 3,
          'orderby' => 'comment_count',
          'order' => 'DESC',
        ]);
      @endphp

      @if ($popular_posts->have_posts())
        <div class="mt-16 text-left">
          <h2 class="text-xl font-bold mb-6 text-center">{{ __('Popular Posts', 'sage') }}</h2>
          <div class="posts-grid">
            @while ($popular_posts->have_posts()) @php($popular_posts->the_post())
              @include('partials.content')
            @endwhile
            @php(wp_reset_postdata())
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection
