@extends('layouts.app')

@section('content')
    <div class="archive-header">
        <div class="container">
            <div class="author-header">
                <img src="{{ get_avatar_url(get_the_author_meta('ID'), ['size' => 200]) }}" alt="{{ get_the_author() }}"
                    class="author-header__avatar" width="100" height="100">
                <div class="author-header__info">
                    <span class="archive-header__label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        {{ __('Author', 'sage') }}
                    </span>
                    <h1 class="archive-header__title">{{ get_the_author() }}</h1>

                    @if (get_the_author_meta('description'))
                        <p class="archive-header__description">{{ get_the_author_meta('description') }}</p>
                    @endif

                    <div class="archive-header__meta">
                        <span class="archive-header__meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14,2 14,8 20,8" />
                            </svg>
                            {{ sprintf(_n('%d article', '%d articles', $wp_query->found_posts, 'sage'), $wp_query->found_posts) }}
                        </span>

                        @if (get_the_author_meta('user_url'))
                            <a href="{{ get_the_author_meta('user_url') }}" class="archive-header__meta-item" target="_blank"
                                rel="noopener">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="2" y1="12" x2="22" y2="12" />
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                </svg>
                                {{ __('Website', 'sage') }}
                            </a>
                        @endif
                    </div>

                    {{-- Author Social Links --}}
                    <div class="author-header__social">
                        @php
                            $social_links = [
                                'twitter' => ['icon' => '<path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>', 'label' => 'Twitter'],
                                'linkedin' => ['icon' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>', 'label' => 'LinkedIn'],
                                'github' => ['icon' => '<path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>', 'label' => 'GitHub'],
                            ];
                        @endphp

                        @foreach ($social_links as $key => $social)
                            @if (get_the_author_meta($key))
                                <a href="{{ get_the_author_meta($key) }}" class="author-header__social-link" target="_blank"
                                    rel="noopener" aria-label="{{ $social['label'] }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">{!! $social['icon'] !!}</svg>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="main container">
        @if (have_posts())
            <div class="posts-grid">
                @while (have_posts()) @php the_post() @endphp
                    <x-card :post="get_post()" />
                @endwhile
            </div>

            @include('partials.pagination')
        @else
            <div class="archive-empty">
                <svg class="archive-empty__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="archive-empty__title">{{ __('No posts yet', 'sage') }}</h2>
                <p class="archive-empty__text">{{ __('This author hasn\'t published any articles yet.', 'sage') }}</p>
                <a href="{{ home_url('/') }}" class="btn btn-primary">{{ __('Back to Home', 'sage') }}</a>
            </div>
        @endif
    </main>
@endsection