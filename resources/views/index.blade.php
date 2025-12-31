@extends('layouts.app')

@section('content')
  <div class="container">
    @include('partials.page-header')

    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'sage') !!}
      </x-alert>

      {!! get_search_form(false) !!}
    @endif

    {{-- Blog Posts Grid --}}
    <div class="posts-grid">
      @while(have_posts()) @php(the_post())
        @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
      @endwhile
    </div>

    {{-- Pagination --}}
    <div class="mt-12">
      {!! get_the_posts_navigation([
        'prev_text' => __('← Older posts', 'sage'),
        'next_text' => __('Newer posts →', 'sage'),
        'class' => 'flex justify-between gap-4',
      ]) !!}
    </div>
  </div>
@endsection
