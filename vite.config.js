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
        // Core styles (always loaded)
        'resources/css/app.css',
        'resources/js/app.js',

        // Editor styles
        'resources/css/editor.css',
        'resources/js/editor.js',

        // Component styles (conditionally loaded via PHP)
        'resources/css/components/hero.css',
        'resources/css/components/single.css',
        'resources/css/components/forms.css',
        'resources/css/components/mega-menu.css',
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
    rollupOptions: {
      output: {
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith('.css')) {
            if (assetInfo.name.includes('hero')) return 'assets/hero-[hash].css';
            if (assetInfo.name.includes('single')) return 'assets/single-[hash].css';
            if (assetInfo.name.includes('forms')) return 'assets/forms-[hash].css';
            if (assetInfo.name.includes('mega-menu')) return 'assets/mega-menu-[hash].css';
            if (assetInfo.name.includes('editor')) return 'assets/editor-[hash].css';
            return 'assets/app-[hash].css';
          }
          return 'assets/[name]-[hash].[ext]';
        },
        chunkFileNames: 'assets/[name]-[hash].js',
        entryFileNames: 'assets/[name]-[hash].js',
      },
    },
  },
})
