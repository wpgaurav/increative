{{--
  Template Name: Canvas (Blank)
  Template Post Type: page

  Completely blank template - no header, no footer.
  Perfect for popups, iframes, or custom designs.
--}}

<!doctype html>
<html @php(language_attributes()) data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php(wp_head())
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body @php(body_class('template-canvas'))>
  @php(wp_body_open())

  @while(have_posts()) @php(the_post())
    <div class="canvas-content">
      @php(the_content())
    </div>
  @endwhile

  @php(wp_footer())
</body>
</html>
