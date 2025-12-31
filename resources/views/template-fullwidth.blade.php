{{--
  Template Name: Full Width
  Template Post Type: page, post

  Full width template without sidebar.
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <article @php(post_class())>
      <div class="container container-wide">
        {{-- Page Header --}}
        @unless(App\is_element_hidden('title'))
          <header class="page-header mb-8">
            <h1 class="page-header__title">
              {!! $title !!}
            </h1>
            @if (has_excerpt())
              <p class="page-header__excerpt text-lg text-muted">
                {!! get_the_excerpt() !!}
              </p>
            @endif
          </header>
        @endunless

        {{-- Featured Image --}}
        @unless(App\is_element_hidden('featured_image'))
          @if (has_post_thumbnail())
            <figure class="page-featured-image mb-8">
              {!! get_the_post_thumbnail(null, 'full', ['class' => 'w-full rounded-xl']) !!}
            </figure>
          @endif
        @endunless

        {{-- Content --}}
        <div class="page-content">
          @php(the_content())
        </div>
      </div>
    </article>
  @endwhile
@endsection
