# Increative Theme

A modern, clean WordPress theme built with **Sage 11**, **Tailwind CSS 4**, and **Laravel Blade**. Perfect for blogs, landing pages, and professional websites.

Inspired by [gauravtiwari.org](https://gauravtiwari.org).

---

## ✨ Features

- **Modern Stack**: Sage 11, Tailwind CSS 4, Vite, Laravel Blade
- **System Fonts**: Uses native system fonts for optimal performance (with custom Google Fonts option)
- **Dark Mode**: Built-in dark mode toggle with system preference detection
- **Responsive Design**: Mobile-first approach with clean, professional layouts
- **Fast Performance**: Optimized CSS and JavaScript, lazy loading, minimal footprint
- **Block Editor Ready**: Full Gutenberg compatibility with custom editor styles
- **Customizer Options**: Typography, social links, header/footer settings, blog options
- **Professional Templates**: Homepage, blog archive, single post, pages, 404
- **Developer Friendly**: PSR-4 autoloading, View Composers, Blade components

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
│   └── setup.php           # Theme setup
├── resources/
│   ├── css/
│   │   ├── app.css         # Main frontend styles
│   │   └── editor.css      # Block editor styles
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

| Section | Options |
|---------|---------|
| **Typography** | Custom Google Font, font weights |
| **Social Links** | Twitter, LinkedIn, GitHub, YouTube, Instagram, Facebook |
| **Header** | Sticky header, dark mode toggle |
| **Footer** | Custom copyright, newsletter toggle |
| **Blog** | Posts per row, reading time, author box, related posts |

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

---

## 📝 Template Hierarchy

| Template | Purpose |
|----------|---------|
| `template-home.blade.php` | Homepage with hero, services, posts |
| `index.blade.php` | Blog archive |
| `single.blade.php` | Single post |
| `page.blade.php` | Standard page |
| `404.blade.php` | Error page |
| `search.blade.php` | Search results |

---

## 🧱 Blade Components

Available components:

- `<x-alert type="info|success|warning|error">` - Alert messages
- `<x-button variant="primary|secondary|ghost">` - Buttons
- `<x-card :post="$post">` - Post cards

---

## 📦 Dependencies

### PHP (Composer)
- roots/acorn
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
