# Increative Theme

A modern, performance-first WordPress theme built with **Sage 11**, **Tailwind CSS 4**, and **Laravel Blade**. Perfect for blogs, landing pages, and professional websites.

Inspired by [gauravtiwari.org](https://gauravtiwari.org).

---

## ✨ Features

### Core
- **Modern Stack**: Sage 11, Tailwind CSS 4, Vite, Laravel Blade
- **System Fonts**: Uses native system fonts for optimal performance (with custom Google Fonts option)
- **Dark Mode**: Built-in dark mode toggle with system preference detection
- **Responsive Design**: Mobile-first approach with clean, professional layouts
- **Block Editor Ready**: Full Gutenberg compatibility with custom editor styles
- **Developer Friendly**: PSR-4 autoloading, View Composers, Blade components

### Performance-First Architecture
- **Critical CSS**: Above-the-fold styles inlined for instant first paint
- **Conditional Asset Loading**: CSS loaded only when needed
- **Code Splitting**: Separate bundles for components
- **Lazy Loading**: Automatic image lazy loading with fetchpriority hints
- **Optimized Scripts**: Deferred loading, removed jQuery Migrate
- **Minimal Footprint**: Removed emoji scripts, optimized heartbeat

### Templates
- **Homepage** (`template-home.blade.php`) - Hero, services, posts grid
- **Blog** (`template-blog.blade.php`) - Blog archive with grid/list toggle
- **Archive** (`archive.blade.php`) - Category, tag, date, taxonomy
- **Author** (`author.blade.php`) - Author archive with bio and social
- **Single Post** (`single.blade.php`) - Full featured with TOC, share, author box
- **Page Templates**: Full width, sidebar left/right, canvas, landing
- **404 Page** - Custom error page
- **Search Results** - Enhanced search page

### Components
| Component          | Description                               |
| ------------------ | ----------------------------------------- |
| `<x-breadcrumbs>`  | SEO-optimized breadcrumbs with schema.org |
| `<x-pagination>`   | Numbered, simple, or load-more pagination |
| `<x-toc>`          | Auto-generated table of contents          |
| `<x-share>`        | Social sharing with native share API      |
| `<x-author-box>`   | Author bio with social links              |
| `<x-newsletter>`   | Newsletter signup forms                   |
| `<x-cta>`          | Call-to-action blocks                     |
| `<x-card>`         | Post cards for grids                      |
| `<x-alert>`        | Alert/notification boxes                  |
| `<x-button>`       | Styled buttons                            |
| `<x-reading-time>` | Estimated reading time                    |
| `<x-post-meta>`    | Post metadata display                     |

### Modules
| Module              | Description                                           |
| ------------------- | ----------------------------------------------------- |
| **RelatedPosts**    | Related posts section on single posts                 |
| **Popup**           | Configurable popups (time/scroll/exit/click triggers) |
| **Callout**         | Info/success/warning/error/tip callouts               |
| **FloatingBar**     | Top/bottom announcement bars                          |
| **TableOfContents** | Auto-generated TOC from headings                      |
| **ReadingProgress** | Reading progress bar + back to top                    |
| **SocialShare**     | Floating and inline share buttons                     |
| **MegaMenu**        | Advanced mega menu system                             |

---

## 🚀 Quick Start

### Requirements

- PHP 8.2+
- WordPress 6.6+
- Node.js 20+
- Composer 2+

### Installation

```bash
# Navigate to themes directory
cd wp-content/themes/increative

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build for production
npm run build

# Or start development server with HMR
npm run dev
```

### Activate Theme

1. Go to **Appearance → Themes** in WordPress admin
2. Activate "Increative"
3. Configure options in **Customize → Increative Theme Options**

---

## 📁 Theme Structure

```
increative/
├── app/                    # PHP application files
│   ├── Providers/          # Service providers
│   ├── View/
│   │   ├── Composers/      # View composers
│   │   └── Walkers/        # Navigation walkers
│   ├── customizer.php      # Theme customizer settings
│   ├── filters.php         # WordPress filters
│   ├── hooks.php           # Theme action hooks
│   ├── metabox.php         # Layout metabox
│   ├── modules.php         # Core modules (Popup, Callout, etc.)
│   ├── modules-extended.php # Extended modules (TOC, Share, etc.)
│   ├── performance.php     # Asset manager & optimizations
│   └── setup.php           # Theme setup
├── resources/
│   ├── css/
│   │   ├── app.css         # Main frontend styles
│   │   ├── critical.css    # Critical above-the-fold CSS
│   │   ├── editor.css      # Block editor styles
│   │   └── components/     # Component CSS (lazy loaded)
│   │       ├── archive.css
│   │       ├── author-box.css
│   │       ├── breadcrumbs.css
│   │       ├── comments.css
│   │       ├── cta.css
│   │       ├── forms.css
│   │       ├── hero.css
│   │       ├── mega-menu.css
│   │       ├── newsletter.css
│   │       ├── pagination.css
│   │       ├── reading-progress.css
│   │       ├── share.css
│   │       ├── single.css
│   │       └── toc.css
│   ├── js/
│   │   ├── app.js          # Main JavaScript
│   │   └── editor.js       # Block editor scripts
│   ├── views/
│   │   ├── layouts/        # Base layouts
│   │   ├── sections/       # Header, footer
│   │   ├── partials/       # Content templates
│   │   └── components/     # Blade components
│   ├── fonts/              # Custom fonts
│   └── images/             # Theme images
├── public/build/           # Compiled assets
├── theme.json              # Block editor configuration
├── vite.config.js          # Vite configuration
└── functions.php           # Theme bootstrap
```

---

## 🎨 Customization

### Theme Options

Access via **Appearance → Customize → Increative Theme Options**:

| Section          | Options                                                 |
| ---------------- | ------------------------------------------------------- |
| **Typography**   | Custom Google Font, font weights                        |
| **Social Links** | Twitter, LinkedIn, GitHub, YouTube, Instagram, Facebook |
| **Header**       | Sticky header, dark mode toggle                         |
| **Footer**       | Custom copyright, newsletter toggle                     |
| **Blog**         | Posts per row, reading time, author box, related posts  |
| **Single Post**  | TOC, share buttons, reading progress, breadcrumbs       |
| **Performance**  | Lazy loading, emoji removal, critical CSS               |

### Per-Post Layout Options

Each post/page has a **Layout Options** metabox with:
- Hide: Header, Footer, Breadcrumbs, Title, Featured Image, Meta, Author Box, Related Posts, Comments, Sidebar
- Layout: Full Width, Transparent Header

### Custom Fonts

1. Go to **Customize → Increative Theme Options → Typography**
2. Enter a Google Font name (e.g., "Inter", "Outfit", "Roboto")
3. Optionally specify font weights (default: 400;500;600;700)

### Colors

Edit the CSS variables in `resources/css/app.css`:

```css
@theme {
  --color-text: #1a1a1a;
  --color-accent: #2563eb;
  --color-bg: #ffffff;
  /* ... */
}
```

---

## 🔧 Development

### Commands

```bash
# Start dev server with HMR
npm run dev

# Build for production
npm run build

# Generate translations
npm run translate
```

### Creating Templates

Add new Blade templates in `resources/views/`:

```blade
{{-- resources/views/template-custom.blade.php --}}
{{--
  Template Name: My Custom Template
--}}

@extends('layouts.app')

@section('content')
  {{-- Your content --}}
@endsection
```

### Creating Components

Add Blade components in `resources/views/components/`:

```blade
{{-- resources/views/components/my-component.blade.php --}}
@props(['title'])

<div {{ $attributes->class(['my-component']) }}>
  <h3>{{ $title }}</h3>
  {{ $slot }}
</div>
```

Use with: `<x-my-component title="Hello">Content</x-my-component>`

### Using Theme Hooks

The theme provides action hooks for extensibility:

```php
// Add content before header
add_action('increative_before_header', function() {
    echo '<div class="announcement">Sale ends today!</div>';
});

// Available hooks:
// Header: increative_before_header, increative_header_start, increative_header_end, increative_after_header
// Content: increative_before_content, increative_content_start, increative_content_end, increative_after_content
// Post: increative_before_post, increative_post_header_start, increative_after_post_content
// Footer: increative_before_footer, increative_footer_start, increative_footer_end, increative_after_footer
```

### Using Modules

Register popups, callouts, and floating bars:

```php
// Register a popup
use App\Modules\Popup;

Popup::register('newsletter', [
    'title' => 'Subscribe to our newsletter',
    'content' => '<form>...</form>',
    'trigger' => 'scroll', // time, scroll, exit, click
    'scroll_percent' => 50,
    'show_once' => true,
]);

// Register a floating bar
use App\Modules\FloatingBar;

FloatingBar::register('promo', [
    'content' => '🎉 Free shipping on orders over $50!',
    'position' => 'top',
    'style' => 'promo',
    'button_text' => 'Shop Now',
    'button_url' => '/shop',
]);
```

---

## ⚡ Performance

### CSS Loading Strategy

1. **Critical CSS** - Inlined in `<head>` for instant first paint
2. **Core CSS** - Main app.css loaded with preload hint
3. **Component CSS** - Loaded conditionally based on page type:
   - `single.css` - Only on single posts
   - `archive.css` - Only on archives
   - `comments.css` - Only when comments are open
   - etc.

### Asset Manager

The `App\Performance\AssetManager` class handles:
- Conditional CSS loading based on page type
- Critical CSS inlining
- Preload hints for important resources
- Deferred loading of non-critical CSS
- Script optimization (async/defer)

### Performance Optimizations

Built-in optimizations:
- Removed WordPress emoji scripts
- Removed jQuery Migrate
- Removed unnecessary meta tags
- Reduced heartbeat frequency
- Native lazy loading for images
- `fetchpriority="high"` for featured images

---

## 📝 Template Hierarchy

| Template                  | Purpose                             |
| ------------------------- | ----------------------------------- |
| `template-home.blade.php` | Homepage with hero, services, posts |
| `template-blog.blade.php` | Blog archive with layout toggle     |
| `archive.blade.php`       | Category/tag/date archives          |
| `author.blade.php`        | Author archive page                 |
| `index.blade.php`         | Blog archive fallback               |
| `single.blade.php`        | Single post                         |
| `page.blade.php`          | Standard page                       |
| `404.blade.php`           | Error page                          |
| `search.blade.php`        | Search results                      |

---

## 🧱 Blade Components

### Breadcrumbs
```blade
<x-breadcrumbs />
<x-breadcrumbs separator="›" />
<x-breadcrumbs class="breadcrumbs--filled" />
```

### Pagination
```blade
<x-pagination />
<x-pagination type="simple" />
<x-pagination type="loadmore" :query="$custom_query" />
```

### Table of Contents
```blade
<x-toc :content="$content" />
<x-toc sticky="true" :minHeadings="4" />
```

### Share Buttons
```blade
<x-share />
<x-share type="floating" />
<x-share type="inline" :networks="['twitter', 'linkedin', 'email']" />
```

### Author Box
```blade
<x-author-box />
<x-author-box :author="$author_id" compact="true" />
```

### Newsletter
```blade
<x-newsletter />
<x-newsletter type="inline" title="Join our list" />
<x-newsletter type="minimal" />
```

### CTA
```blade
<x-cta 
  title="Ready to get started?" 
  description="Join thousands of happy users."
  buttonText="Sign Up Free"
  buttonUrl="/signup"
/>

<x-cta type="dark" ... />
<x-cta type="gradient" ... />
```

---

## 📦 Dependencies

### PHP (Composer)
- roots/acorn ^5.0
- log1x/sage-directives (optional)

### JavaScript (npm)
- tailwindcss ^4.0.9
- vite ^6.2.0
- laravel-vite-plugin
- @roots/vite-plugin
- @tailwindcss/vite

---

## 📄 License

MIT License - see [LICENSE.md](LICENSE.md)

---

## 🙏 Credits

- [Roots Team](https://roots.io) for Sage
- [Adam Wathan](https://adamwathan.me) for Tailwind CSS
- Inspired by [gauravtiwari.org](https://gauravtiwari.org)
