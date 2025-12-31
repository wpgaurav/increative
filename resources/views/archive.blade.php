@extends('layouts.app')

@section('content')
    <div class="archive-header">
        <div class="container">
            @if (is_category())
                <span class="archive-header__label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M3 3h6l2 3h10v13H3z" />
                    </svg>
                    {{ __('Category', 'sage') }}
                </span>
                <h1 class="archive-header__title">{{ single_cat_title('', false) }}</h1>
                @if (category_description())
                    <p class="archive-header__description">{!! category_description() !!}</p>
                @endif
                <div class="archive-header__meta">
                    <span class="archive-header__meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14,2 14,8 20,8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10,9 9,9 8,9" />
                        </svg>
                        {{ sprintf(_n('%d article', '%d articles', $wp_query->found_posts, 'sage'), $wp_query->found_posts) }}
                    </span>
                </div>

            @elseif (is_tag())
                <span class="archive-header__label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                        <line x1="7" y1="7" x2="7.01" y2="7" />
                    </svg>
                    {{ __('Tag', 'sage') }}
                </span>
                <h1 class="archive-header__title">#{{ single_tag_title('', false) }}</h1>
                @if (tag_description())
                    <p class="archive-header__description">{!! tag_description() !!}</p>
                @endif

            @elseif (is_date())
                <span class="archive-header__label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    {{ __('Archive', 'sage') }}
                </span>
                <h1 class="archive-header__title">
                    @if (is_day())
                        {{ get_the_date() }}
                    @elseif (is_month())
                        {{ get_the_date('F Y') }}
                    @elseif (is_year())
                        {{ get_the_date('Y') }}
                    @endif
                </h1>

            @elseif (is_post_type_archive())
                <span class="archive-header__label">
                    {{ post_type_archive_title('', false) }}
                </span>
                <h1 class="archive-header__title">{{ post_type_archive_title('', false) }}</h1>

            @elseif (is_tax())
                <span class="archive-header__label">
                    {{ get_queried_object()->taxonomy }}
                </span>
                <h1 class="archive-header__title">{{ single_term_title('', false) }}</h1>
                @if (term_description())
                    <p class="archive-header__description">{!! term_description() !!}</p>
                @endif

            @else
                <h1 class="archive-header__title">{{ __('Archives', 'sage') }}</h1>
            @endif
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
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h2 class="archive-empty__title">{{ __('Nothing found', 'sage') }}</h2>
                <p class="archive-empty__text">
                    {{ __('No content matched your criteria. Try searching for something else.', 'sage') }}</p>
                {!! get_search_form(false) !!}
            </div>
        @endif
    </main>
@endsection