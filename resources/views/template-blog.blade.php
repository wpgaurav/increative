{{--
Template Name: Blog
--}}

@extends('layouts.app')

@section('content')
    <div class="archive-header">
        <div class="container">
            <span class="archive-header__label">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" />
                </svg>
                {{ __('Blog', 'sage') }}
            </span>
            <h1 class="archive-header__title">{{ get_the_title() ?: __('Latest Articles', 'sage') }}</h1>
            @if (has_excerpt())
                <p class="archive-header__description">{{ get_the_excerpt() }}</p>
            @else
                <p class="archive-header__description">{{ __('Thoughts, stories, and ideas from our team.', 'sage') }}</p>
            @endif
        </div>
    </div>

    <main class="main container">
        @php
            $paged = get_query_var('paged') ?: 1;
            $posts_per_page = get_option('posts_per_page');

            $args = [
                'post_type' => 'post',
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post_status' => 'publish',
            ];

            $blog_query = new WP_Query($args);
        @endphp

        @if ($blog_query->have_posts())
            {{-- Archive Layout Toggle --}}
            <div class="archive-layout">
                <span class="archive-layout__count">
                    {{ sprintf(
                __('Showing %1$d–%2$d of %3$d articles', 'sage'),
                (($paged - 1) * $posts_per_page) + 1,
                min($paged * $posts_per_page, $blog_query->found_posts),
                $blog_query->found_posts
            ) }}
                </span>
                <div class="archive-layout__toggle">
                    <button class="archive-layout__btn is-active" data-layout="grid" aria-label="{{ __('Grid view', 'sage') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                    </button>
                    <button class="archive-layout__btn" data-layout="list" aria-label="{{ __('List view', 'sage') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <line x1="8" y1="6" x2="21" y2="6" />
                            <line x1="8" y1="12" x2="21" y2="12" />
                            <line x1="8" y1="18" x2="21" y2="18" />
                            <line x1="3" y1="6" x2="3.01" y2="6" />
                            <line x1="3" y1="12" x2="3.01" y2="12" />
                            <line x1="3" y1="18" x2="3.01" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Posts Grid --}}
            <div class="posts-grid" id="posts-container">
                @while ($blog_query->have_posts()) @php $blog_query->the_post() @endphp
                    <x-card :post="get_post()" />
                @endwhile
            </div>

            @php wp_reset_postdata() @endphp

            {{-- Pagination --}}
            @include('partials.pagination', ['query' => $blog_query])

        @else
            <div class="archive-empty">
                <svg class="archive-empty__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="archive-empty__title">{{ __('No posts yet', 'sage') }}</h2>
                <p class="archive-empty__text">{{ __('Check back soon for new content!', 'sage') }}</p>
                <a href="{{ home_url('/') }}" class="btn btn-primary">{{ __('Back to Home', 'sage') }}</a>
            </div>
        @endif
    </main>
@endsection