{{--
  Template Name: Sidebar Right
  Template Post Type: page, post

  Two-column layout with sidebar on the right.
--}}

@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="layout-sidebar-right">
      {{-- Main Content --}}
      <main class="layout-content">
        @while(have_posts()) @php(the_post())
          <article @php(post_class())>
            {{-- Header --}}
            @unless(App\is_element_hidden('title'))
              <header class="entry-header mb-6">
                <h1 class="entry-title">{!! $title !!}</h1>
              </header>
            @endunless

            {{-- Featured Image --}}
            @unless(App\is_element_hidden('featured_image'))
              @if (has_post_thumbnail())
                <figure class="entry-thumbnail mb-6">
                  {!! get_the_post_thumbnail(null, 'large', ['class' => 'w-full rounded-lg']) !!}
                </figure>
              @endif
            @endunless

            {{-- Content --}}
            <div class="entry-content post-content">
              @php(the_content())
            </div>
          </article>
        @endwhile
      </main>

      {{-- Sidebar --}}
      <aside class="sidebar sidebar--right">
        @php(dynamic_sidebar('sidebar-primary'))
      </aside>
    </div>
  </div>
@endsection

@push('styles')
<style>
  .layout-sidebar-right {
    display: grid;
    gap: var(--space-xl);
  }
  @media (min-width: 1024px) {
    .layout-sidebar-right {
      grid-template-columns: 1fr 280px;
    }
  }
</style>
@endpush
