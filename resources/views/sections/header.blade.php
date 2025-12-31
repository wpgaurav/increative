@php
  use function App\do_before_header;
  use function App\do_header_start;
  use function App\do_before_nav;
  use function App\do_after_nav;
  use function App\do_header_end;
  use function App\do_after_header;
  use function App\is_element_hidden;
  use function App\is_transparent_header;
@endphp

{{-- Before Header Hook --}}
@php(do_before_header())

@unless(is_element_hidden('header'))
<header class="site-header @if(is_transparent_header()) site-header--transparent @endif" id="site-header">
  @php(do_header_start())
  
  <div class="container container-wide">
    <div class="site-header__inner">
      {{-- Logo --}}
      <a class="site-logo" href="{{ home_url('/') }}">
        @if (has_custom_logo())
          {!! get_custom_logo() !!}
        @else
          {!! $siteName !!}
        @endif
      </a>

      {{-- Before Navigation Hook --}}
      @php(do_before_nav())

      {{-- Primary Navigation (Desktop) --}}
      @if (has_nav_menu('primary_navigation'))
        <nav class="site-nav" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'container' => false,
            'menu_class' => 'nav-list',
            'echo' => false,
            'walker' => new App\View\Walkers\MegaMenuWalker(),
          ]) !!}
        </nav>
      @endif

      {{-- After Navigation Hook --}}
      @php(do_after_nav())

      {{-- Header Actions --}}
      <div class="site-header__actions">
        {{-- Dark Mode Toggle --}}
        @if (get_theme_mod('increative_dark_mode_toggle', true))
          <button 
            type="button" 
            class="btn-ghost header-action" 
            id="theme-toggle"
            aria-label="{{ __('Toggle dark mode', 'sage') }}"
          >
            <svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="5"/>
              <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
            </svg>
            <svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
          </button>
        @endif

        {{-- Search Toggle --}}
        <button 
          type="button" 
          class="btn-ghost header-action" 
          id="search-toggle"
          aria-label="{{ __('Open search', 'sage') }}"
        >
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
          </svg>
        </button>

        {{-- CTA Button (Optional) --}}
        @if ($cta_text = get_theme_mod('increative_header_cta_text'))
          <a href="{{ get_theme_mod('increative_header_cta_url', '#') }}" class="btn btn-primary btn-sm hidden lg:inline-flex">
            {{ $cta_text }}
          </a>
        @endif

        {{-- Mobile Menu Toggle --}}
        <button 
          type="button" 
          class="mobile-menu-toggle" 
          id="mobile-menu-toggle"
          aria-label="{{ __('Open menu', 'sage') }}"
          aria-expanded="false"
          aria-controls="mobile-menu"
        >
          <svg class="mobile-menu-toggle__icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  @php(do_header_end())
</header>

{{-- Mobile Menu --}}
<div class="mobile-menu" id="mobile-menu" aria-label="{{ __('Mobile navigation', 'sage') }}" hidden>
  <div class="mobile-menu__header">
    <a class="site-logo" href="{{ home_url('/') }}">
      {!! $siteName !!}
    </a>
    <button 
      type="button" 
      class="mobile-menu__close" 
      id="mobile-menu-close"
      aria-label="{{ __('Close menu', 'sage') }}"
    >
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>
  </div>

  <div class="mobile-menu__content">
    @if (has_nav_menu('primary_navigation'))
      <nav class="mobile-menu__nav">
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'container' => false,
          'menu_class' => 'mobile-nav-list',
          'echo' => false,
        ]) !!}
      </nav>
    @endif
  </div>

  <div class="mobile-menu__footer">
    {{-- Social Links --}}
    <div class="mobile-menu__social">
      @if ($twitter = get_theme_mod('increative_twitter'))
        <a href="{{ $twitter }}" target="_blank" rel="noopener" aria-label="Twitter">
          <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
      @endif
      @if ($linkedin = get_theme_mod('increative_linkedin'))
        <a href="{{ $linkedin }}" target="_blank" rel="noopener" aria-label="LinkedIn">
          <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        </a>
      @endif
    </div>
  </div>
</div>

{{-- Search Overlay --}}
<div class="search-overlay" id="search-overlay" hidden>
  <div class="search-overlay__container">
    <form class="search-overlay__form" role="search" method="get" action="{{ home_url('/') }}">
      <input 
        type="search" 
        name="s" 
        class="search-overlay__input"
        placeholder="{{ __('Search...', 'sage') }}"
        autocomplete="off"
      >
      <button type="submit" class="search-overlay__submit">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/>
          <path d="m21 21-4.35-4.35"/>
        </svg>
      </button>
    </form>
    <button class="search-overlay__close" id="search-close" aria-label="{{ __('Close search', 'sage') }}">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>
  </div>
</div>
@endunless

{{-- After Header Hook --}}
@php(do_after_header())
