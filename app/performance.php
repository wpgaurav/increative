<?php

/**
 * Performance Asset Manager
 * 
 * Handles conditional loading of CSS/JS assets for optimal performance.
 * Only loads what's needed on each page.
 */

namespace App\Performance;

/**
 * Asset Manager Class
 */
class AssetManager {
    
    /**
     * Registered component styles.
     *
     * @var array
     */
    private static $component_styles = [];
    
    /**
     * Critical CSS content.
     *
     * @var string|null
     */
    private static $critical_css = null;
    
    /**
     * Initialize the asset manager.
     */
    public static function init() {
        // Register core assets
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_core_assets'], 1);
        
        // Register conditional assets
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_conditional_assets'], 10);
        
        // Inline critical CSS
        add_action('wp_head', [self::class, 'inline_critical_css'], 1);
        
        // Add preload hints
        add_action('wp_head', [self::class, 'add_preload_hints'], 2);
        
        // Add resource hints
        add_filter('wp_resource_hints', [self::class, 'add_resource_hints'], 10, 2);
        
        // Defer non-critical CSS
        add_filter('style_loader_tag', [self::class, 'defer_non_critical_css'], 10, 4);
        
        // Add async/defer to scripts
        add_filter('script_loader_tag', [self::class, 'optimize_script_loading'], 10, 3);
    }
    
    /**
     * Register a component style.
     *
     * @param string $handle Style handle
     * @param string $src Relative path to CSS file
     * @param callable|bool $condition Condition callback or boolean
     */
    public static function register_style($handle, $src, $condition = true) {
        self::$component_styles[$handle] = [
            'src' => $src,
            'condition' => $condition,
        ];
    }
    
    /**
     * Enqueue core assets (always loaded).
     */
    public static function enqueue_core_assets() {
        // Main app styles (deferred, non-critical)
        if (self::is_vite_running()) {
            // Development: Vite handles everything
            return;
        }
        
        // Production: Load from manifest
        $manifest = self::get_manifest();
        
        if (!$manifest) {
            return;
        }
        
        // Enqueue main CSS
        $app_css = $manifest['resources/css/app.css']['file'] ?? null;
        if ($app_css) {
            wp_enqueue_style(
                'increative-app',
                get_theme_file_uri('public/build/' . $app_css),
                [],
                null
            );
        }
        
        // Enqueue main JS
        $app_js = $manifest['resources/js/app.js']['file'] ?? null;
        if ($app_js) {
            wp_enqueue_script(
                'increative-app',
                get_theme_file_uri('public/build/' . $app_js),
                [],
                null,
                true
            );
        }
    }
    
    /**
     * Enqueue conditional assets based on page type.
     */
    public static function enqueue_conditional_assets() {
        if (self::is_vite_running()) {
            return;
        }
        
        $manifest = self::get_manifest();
        if (!$manifest) {
            return;
        }
        
        // Single post/page styles
        if (is_singular('post')) {
            self::enqueue_component('single', $manifest);
            self::enqueue_component('author-box', $manifest);
            self::enqueue_component('share', $manifest);
            self::enqueue_component('reading-progress', $manifest);
            
            // TOC if enabled
            if (self::has_toc()) {
                self::enqueue_component('toc', $manifest);
            }
            
            // Comments if open
            if (comments_open() || get_comments_number()) {
                self::enqueue_component('comments', $manifest);
            }
        }
        
        // Archive pages
        if (is_archive() || is_home() || is_search()) {
            self::enqueue_component('archive', $manifest);
            self::enqueue_component('pagination', $manifest);
        }
        
        // Hero on front page or pages with hero
        if (is_front_page() || is_page_template('template-home.blade.php')) {
            self::enqueue_component('hero', $manifest);
        }
        
        // Breadcrumbs (most pages except home)
        if (!is_front_page()) {
            self::enqueue_component('breadcrumbs', $manifest);
        }
        
        // Forms on contact pages or pages with forms
        if (is_page_template('template-contact.blade.php') || self::page_has_form()) {
            self::enqueue_component('forms', $manifest);
        }
        
        // Newsletter
        if (self::should_show_newsletter()) {
            self::enqueue_component('newsletter', $manifest);
        }
        
        // CTA blocks
        if (self::has_cta_blocks()) {
            self::enqueue_component('cta', $manifest);
        }
        
        // Mega menu if has complex navigation
        if (has_nav_menu('primary') && self::has_mega_menu()) {
            self::enqueue_component('mega-menu', $manifest);
        }
    }
    
    /**
     * Enqueue a specific component CSS.
     *
     * @param string $component Component name
     * @param array $manifest Vite manifest
     */
    private static function enqueue_component($component, $manifest) {
        $key = "resources/css/components/{$component}.css";
        $file = $manifest[$key]['file'] ?? null;
        
        if ($file) {
            wp_enqueue_style(
                "increative-{$component}",
                get_theme_file_uri('public/build/' . $file),
                ['increative-app'],
                null
            );
        }
    }
    
    /**
     * Inline critical CSS in head.
     */
    public static function inline_critical_css() {
        if (self::is_vite_running()) {
            return;
        }
        
        $manifest = self::get_manifest();
        if (!$manifest) {
            return;
        }
        
        $critical_file = $manifest['resources/css/critical.css']['file'] ?? null;
        
        if ($critical_file) {
            $critical_path = get_theme_file_path('public/build/' . $critical_file);
            
            if (file_exists($critical_path)) {
                $css = file_get_contents($critical_path);
                echo '<style id="critical-css">' . $css . '</style>' . "\n";
            }
        }
    }
    
    /**
     * Add preload hints for critical resources.
     */
    public static function add_preload_hints() {
        if (self::is_vite_running()) {
            return;
        }
        
        $manifest = self::get_manifest();
        if (!$manifest) {
            return;
        }
        
        // Preload main CSS
        $app_css = $manifest['resources/css/app.css']['file'] ?? null;
        if ($app_css) {
            printf(
                '<link rel="preload" href="%s" as="style">%s',
                esc_url(get_theme_file_uri('public/build/' . $app_css)),
                "\n"
            );
        }
        
        // Preload main JS
        $app_js = $manifest['resources/js/app.js']['file'] ?? null;
        if ($app_js) {
            printf(
                '<link rel="preload" href="%s" as="script">%s',
                esc_url(get_theme_file_uri('public/build/' . $app_js)),
                "\n"
            );
        }
    }
    
    /**
     * Add resource hints (preconnect, dns-prefetch).
     *
     * @param array $hints Existing hints
     * @param string $relation Relation type
     * @return array Modified hints
     */
    public static function add_resource_hints($hints, $relation) {
        if ($relation === 'preconnect') {
            // Google Fonts if custom font is set
            if (get_theme_mod('increative_custom_font', '')) {
                $hints[] = [
                    'href' => 'https://fonts.googleapis.com',
                    'crossorigin' => 'anonymous',
                ];
                $hints[] = [
                    'href' => 'https://fonts.gstatic.com',
                    'crossorigin' => 'anonymous',
                ];
            }
        }
        
        return $hints;
    }
    
    /**
     * Defer non-critical CSS loading.
     *
     * @param string $html Link tag HTML
     * @param string $handle Style handle
     * @param string $href Style URL
     * @param string $media Media type
     * @return string Modified HTML
     */
    public static function defer_non_critical_css($html, $handle, $href, $media) {
        // List of styles that should be deferred
        $deferred = [
            'increative-archive',
            'increative-comments',
            'increative-cta',
            'increative-newsletter',
        ];
        
        if (in_array($handle, $deferred, true)) {
            // Use media="print" trick for deferred loading
            $html = str_replace(
                "media='all'",
                "media='print' onload=\"this.media='all'\"",
                $html
            );
            
            // Add noscript fallback
            $html .= '<noscript><link rel="stylesheet" href="' . esc_url($href) . '"></noscript>';
        }
        
        return $html;
    }
    
    /**
     * Optimize script loading with async/defer.
     *
     * @param string $tag Script tag
     * @param string $handle Script handle
     * @param string $src Script URL
     * @return string Modified tag
     */
    public static function optimize_script_loading($tag, $handle, $src) {
        // Add defer to main app script
        if ($handle === 'increative-app') {
            return str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Check if Vite dev server is running.
     *
     * @return bool
     */
    private static function is_vite_running() {
        $hot_file = get_theme_file_path('public/hot');
        return file_exists($hot_file);
    }
    
    /**
     * Get Vite manifest.
     *
     * @return array|null
     */
    private static function get_manifest() {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifest_path = get_theme_file_path('public/build/.vite/manifest.json');
            
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            } else {
                $manifest = false;
            }
        }
        
        return $manifest ?: null;
    }
    
    /**
     * Check if current page has TOC.
     *
     * @return bool
     */
    private static function has_toc() {
        if (!is_singular()) {
            return false;
        }
        
        $content = get_the_content();
        // Check for headings in content
        return preg_match('/<h[2-4][^>]*>/i', $content) > 0;
    }
    
    /**
     * Check if page has a form.
     *
     * @return bool
     */
    private static function page_has_form() {
        if (!is_singular()) {
            return false;
        }
        
        $content = get_the_content();
        
        // Check for form elements or popular form plugin shortcodes
        return (
            strpos($content, '<form') !== false ||
            strpos($content, '[contact-form') !== false ||
            strpos($content, '[wpforms') !== false ||
            strpos($content, '[gravityform') !== false
        );
    }
    
    /**
     * Check if newsletter should be shown.
     *
     * @return bool
     */
    private static function should_show_newsletter() {
        return get_theme_mod('increative_footer_newsletter', true);
    }
    
    /**
     * Check if page has CTA blocks.
     *
     * @return bool
     */
    private static function has_cta_blocks() {
        if (!is_singular()) {
            return false;
        }
        
        $content = get_the_content();
        
        return (
            strpos($content, 'wp-block-buttons') !== false ||
            strpos($content, 'cta-') !== false ||
            has_block('core/button')
        );
    }
    
    /**
     * Check if mega menu is enabled.
     *
     * @return bool
     */
    private static function has_mega_menu() {
        // Check if any menu items have mega menu enabled
        $locations = get_nav_menu_locations();
        
        if (empty($locations['primary'])) {
            return false;
        }
        
        $menu_items = wp_get_nav_menu_items($locations['primary']);
        
        if (!$menu_items) {
            return false;
        }
        
        foreach ($menu_items as $item) {
            if (get_post_meta($item->ID, '_mega_menu', true)) {
                return true;
            }
        }
        
        return false;
    }
}

// Initialize the asset manager
add_action('after_setup_theme', [AssetManager::class, 'init']);

/**
 * ============================================
 * PERFORMANCE OPTIMIZATIONS
 * ============================================
 */

/**
 * Remove emoji scripts and styles.
 */
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
});

/**
 * Remove jQuery Migrate.
 */
add_action('wp_default_scripts', function ($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        
        if ($script->deps) {
            $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }
});

/**
 * Remove WP embed script.
 */
add_action('wp_footer', function () {
    wp_dequeue_script('wp-embed');
});

/**
 * Remove Gutenberg block styles if not using blocks.
 */
add_action('wp_enqueue_scripts', function () {
    // Only remove on frontend, not in editor
    if (!is_singular() || !has_blocks(get_the_content())) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style'); // WooCommerce blocks
    }
}, 100);

/**
 * Lazy load images by default.
 */
add_filter('wp_get_attachment_image_attributes', function ($attr) {
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    
    if (!isset($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }
    
    return $attr;
});

/**
 * Add fetchpriority to above-the-fold images.
 */
add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) {
    // Detect if this is likely the featured image or hero image
    if (in_array($size, ['full', 'large', 'hero', 'featured'], true)) {
        // Only on first image per page
        static $first_image = true;
        
        if ($first_image && is_singular()) {
            $attr['fetchpriority'] = 'high';
            $attr['loading'] = 'eager';
            $first_image = false;
        }
    }
    
    return $attr;
}, 10, 3);

/**
 * Optimize script loading.
 */
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    // Scripts to defer
    $defer_scripts = [
        'comment-reply',
        'wp-embed',
    ];
    
    if (in_array($handle, $defer_scripts, true)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}, 10, 3);

/**
 * Remove unnecessary meta tags.
 */
add_action('init', function () {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
});

/**
 * Reduce heartbeat frequency.
 */
add_filter('heartbeat_settings', function ($settings) {
    $settings['interval'] = 60; // 60 seconds instead of 15
    return $settings;
});
