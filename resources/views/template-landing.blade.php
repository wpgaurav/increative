{{--
  Template Name: Landing Page
  Template Post Type: page

  A minimal landing page template with:
  - No header/footer (optional)
  - Full-width layout
  - Clean canvas for page builders/blocks
--}}

@extends('layouts.app')

@php
  // Override layout options for landing page
  $hide_header = get_post_meta(get_the_ID(), '_increative_layout', true)['hide_header'] ?? false;
  $hide_footer = get_post_meta(get_the_ID(), '_increative_layout', true)['hide_footer'] ?? false;
@endphp

@section('content')
  @while(have_posts()) @php(the_post())
    <article @php(post_class('landing-page'))>
      {{-- Full Width Content --}}
      <div class="landing-page__content">
        @php(the_content())
      </div>
    </article>
  @endwhile
@endsection

@push('styles')
<style>
  .landing-page {
    max-width: none;
  }
  .landing-page__content {
    /* Allow full-width blocks */
  }
  .landing-page__content > * {
    max-width: var(--content);
    margin-inline: auto;
    padding-inline: var(--gutter);
  }
  .landing-page__content > .alignwide {
    max-width: var(--wide);
  }
  .landing-page__content > .alignfull {
    max-width: none;
    padding-inline: 0;
  }
</style>
@endpush
