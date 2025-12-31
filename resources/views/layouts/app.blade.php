<!doctype html>
<html @php(language_attributes()) data-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body @php(body_class('bg-bg text-text'))>
    @php(wp_body_open())

    {{-- Skip to content link --}}
    <a class="sr-only focus:not-sr-only" href="#main">
      {{ __('Skip to content', 'sage') }}
    </a>

    {{-- Reading Progress Bar (for single posts) --}}
    @if (is_singular('post'))
      <div class="reading-progress fixed top-0 left-0 h-1 bg-accent origin-left z-[110]" style="transform: scaleX(0); width: 100%;"></div>
    @endif

    <div id="app">
      @include('sections.header')

      <main id="main" class="main">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar container">
          @yield('sidebar')
        </aside>
      @endif

      @include('sections.footer')
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
