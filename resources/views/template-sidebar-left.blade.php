{{--
  Template Name: Sidebar Left
  Template Post Type: page, post

  Two-column layout with sidebar on the left.
--}}

@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="layout-sidebar-left">
      {{-- Sidebar --}}
      <aside class="sidebar sidebar--left">
        @php(dynamic_sidebar('sidebar-primary'))
      </aside>

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
    </div>
  </div>
@endsection

@push('styles')
<style>
  .layout-sidebar-left {
    display: grid;
    gap: var(--space-xl);
  }
  @media (min-width: 1024px) {
    .layout-sidebar-left {
      grid-template-columns: 280px 1fr;
    }
  }
  .sidebar--left {
    order: -1;
  }
  @media (max-width: 1023px) {
    .sidebar--left {
      order: 1;
    }
  }
</style>
@endpush
