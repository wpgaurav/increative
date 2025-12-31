{{--
  Template Name: Home Page
--}}

@extends('layouts.app')

@section('content')
  {{-- Hero Section --}}
  <section class="hero">
    <div class="container">
      <div class="hero__content">
        <span class="hero__tagline">{{ __('Welcome', 'sage') }}</span>
        <h1 class="hero__title">
          {{ get_the_title() ?: __('Building websites that actually work.', 'sage') }}
        </h1>
        <p class="hero__description">
          {!! get_the_excerpt() ?: __('Not just pretty mockups. Real sites that load fast, rank well, and convert visitors into customers.', 'sage') !!}
        </p>
        <div class="hero__actions">
          <a href="{{ home_url('/services') }}" class="btn btn-primary">
            {{ __('Start a project', 'sage') }} →
          </a>
          <a href="{{ home_url('/work') }}" class="btn btn-secondary">
            {{ __('See my work', 'sage') }}
          </a>
        </div>

        {{-- Author Info --}}
        <div class="hero__author">
          {!! get_avatar(get_option('admin_email'), 48, '', '', ['class' => 'hero__author-avatar']) !!}
          <div class="hero__author-info">
            <div class="hero__author-name">{{ get_bloginfo('name') }}</div>
            <div class="hero__author-role">{{ __('Developer · Educator · Marketer', 'sage') }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Services Section --}}
  <section class="section section--alt">
    <div class="container">
      <div class="section__header text-center">
        <h2 class="section__title">{{ __('Services That Move the Needle', 'sage') }}</h2>
        <p class="section__description">{{ __('Everything you need to grow your online presence.', 'sage') }}</p>
      </div>

      <div class="services-grid">
        @php
          $services = [
            ['icon' => 'strategy', 'title' => 'Digital Strategy', 'desc' => 'Tailored strategies that help you reach the right audience.'],
            ['icon' => 'code', 'title' => 'Web Development', 'desc' => 'Custom solutions that enhance your presence and accelerate growth.'],
            ['icon' => 'wordpress', 'title' => 'WordPress', 'desc' => 'Powerful, user-friendly sites built to your specifications.'],
            ['icon' => 'search', 'title' => 'SEO', 'desc' => 'Expert strategies that improve rankings and drive organic traffic.'],
            ['icon' => 'content', 'title' => 'Content Marketing', 'desc' => 'Targeted content that boosts visibility and engagement.'],
            ['icon' => 'speed', 'title' => 'Performance', 'desc' => 'Blazing fast load times and smooth user experiences.'],
          ];
        @endphp

        @foreach ($services as $service)
          <div class="service-card">
            <div class="service-card__icon">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="service-card__title">{{ __($service['title'], 'sage') }}</h3>
            <p class="service-card__description">{{ __($service['desc'], 'sage') }}</p>
          </div>
        @endforeach
      </div>

      <div class="text-center mt-8">
        <a href="{{ home_url('/services') }}" class="btn btn-secondary">
          {{ __('View all services', 'sage') }} →
        </a>
      </div>
    </div>
  </section>

  {{-- Latest Posts Section --}}
  <section class="section">
    <div class="container">
      <div class="section__header">
        <h2 class="section__title">{{ __('Latest from the Blog', 'sage') }}</h2>
        <p class="section__description">{{ __('Thoughts on web development, WordPress, and growing online.', 'sage') }}</p>
      </div>

      @php
        $latest_posts = new WP_Query([
          'post_type' => 'post',
          'posts_per_page' => 6,
        ]);
      @endphp

      @if ($latest_posts->have_posts())
        <div class="posts-grid">
          @while ($latest_posts->have_posts()) @php($latest_posts->the_post())
            @include('partials.content')
          @endwhile
          @php(wp_reset_postdata())
        </div>

        <div class="text-center mt-8">
          <a href="{{ get_permalink(get_option('page_for_posts')) }}" class="btn btn-secondary">
            {{ __('View all posts', 'sage') }} →
          </a>
        </div>
      @endif
    </div>
  </section>

  {{-- Newsletter Section --}}
  <section class="section section--alt">
    <div class="container container-narrow">
      <div class="newsletter">
        <h2 class="newsletter__title">{{ __('Stay in the loop', 'sage') }}</h2>
        <p class="newsletter__description">
          {{ __('Get the latest posts and updates delivered to your inbox. No spam, ever.', 'sage') }}
        </p>
        <form class="newsletter__form" action="#" method="post">
          <input 
            type="email" 
            name="email" 
            placeholder="{{ __('Enter your email', 'sage') }}"
            class="newsletter__input"
            required
          >
          <button type="submit" class="btn btn-primary">
            {{ __('Subscribe', 'sage') }}
          </button>
        </form>
      </div>
    </div>
  </section>

  {{-- Page Content (if any) --}}
  @while(have_posts()) @php(the_post())
    @if (get_the_content())
      <section class="section">
        <div class="container container-narrow">
          <div class="post-content">
            @php(the_content())
          </div>
        </div>
      </section>
    @endif
  @endwhile
@endsection
