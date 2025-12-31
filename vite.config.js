import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin'
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';

export default defineConfig({
  // Base path for WordPress Studio
  base: '/wp-content/themes/increative/public/build/',

  plugins: [
    tailwindcss(),

    laravel({
      input: [
        // ============================================
        // CORE ASSETS (Always Loaded)
        // ============================================
        'resources/css/app.css',
        'resources/js/app.js',

        // Critical CSS (Inlined in head)
        'resources/css/critical.css',

        // Editor assets
        'resources/css/editor.css',
        'resources/js/editor.js',

        // ============================================
        // COMPONENT CSS (Conditionally Loaded)
        // ============================================

        // Layout Components
        'resources/css/components/hero.css',
        'resources/css/components/mega-menu.css',

        // Single Post Components  
        'resources/css/components/single.css',
        'resources/css/components/toc.css',
        'resources/css/components/author-box.css',
        'resources/css/components/share.css',
        'resources/css/components/comments.css',
        'resources/css/components/reading-progress.css',

        // Archive Components
        'resources/css/components/archive.css',
        'resources/css/components/pagination.css',
        'resources/css/components/breadcrumbs.css',

        // Global Components
        'resources/css/components/forms.css',
        'resources/css/components/newsletter.css',
        'resources/css/components/cta.css',
      ],
      refresh: true,
    }),

    wordpressPlugin(),

    // Generate theme.json from Tailwind
    wordpressThemeJson({
      disableTailwindColors: false,
      disableTailwindFonts: false,
      disableTailwindFontSizes: false,
    }),
  ],

  resolve: {
    alias: {
      '@scripts': '/resources/js',
      '@styles': '/resources/css',
      '@fonts': '/resources/fonts',
      '@images': '/resources/images',
    },
  },

  server: {
    cors: true,
    strictPort: true,
    hmr: {
      host: 'localhost',
    },
  },

  build: {
    outDir: 'public/build',
    manifest: true,
    cssCodeSplit: true,
    cssMinify: 'lightningcss',
    minify: 'esbuild',
    rollupOptions: {
      output: {
        // Deterministic file naming for caching
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith('.css')) {
            // Extract component name from path
            const name = assetInfo.name.replace('.css', '');
            if (name.includes('critical')) return 'assets/critical-[hash].css';
            if (name.includes('hero')) return 'assets/hero-[hash].css';
            if (name.includes('single')) return 'assets/single-[hash].css';
            if (name.includes('forms')) return 'assets/forms-[hash].css';
            if (name.includes('mega-menu')) return 'assets/mega-menu-[hash].css';
            if (name.includes('toc')) return 'assets/toc-[hash].css';
            if (name.includes('author-box')) return 'assets/author-box-[hash].css';
            if (name.includes('share')) return 'assets/share-[hash].css';
            if (name.includes('comments')) return 'assets/comments-[hash].css';
            if (name.includes('reading-progress')) return 'assets/reading-progress-[hash].css';
            if (name.includes('archive')) return 'assets/archive-[hash].css';
            if (name.includes('pagination')) return 'assets/pagination-[hash].css';
            if (name.includes('breadcrumbs')) return 'assets/breadcrumbs-[hash].css';
            if (name.includes('newsletter')) return 'assets/newsletter-[hash].css';
            if (name.includes('cta')) return 'assets/cta-[hash].css';
            if (name.includes('editor')) return 'assets/editor-[hash].css';
            return 'assets/app-[hash].css';
          }
          return 'assets/[name]-[hash].[ext]';
        },
        chunkFileNames: 'assets/[name]-[hash].js',
        entryFileNames: 'assets/[name]-[hash].js',

        // Manual chunks for better caching
        manualChunks: (id) => {
          if (id.includes('node_modules')) {
            return 'vendor';
          }
        },
      },
    },
    // Target modern browsers for smaller bundles
    target: 'es2020',
    // Generate source maps for production debugging
    sourcemap: false,
  },

  // Optimize dependencies
  optimizeDeps: {
    include: [],
    exclude: [],
  },
})
