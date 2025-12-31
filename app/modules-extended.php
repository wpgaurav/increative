<?php

/**
 * Table of Contents Module
 * 
 * Auto-generates TOC from post content headings.
 * Adds IDs to headings if missing.
 */

namespace App\Modules;

class TableOfContents {
    
    /**
     * Store extracted headings.
     *
     * @var array
     */
    private static $headings = [];
    
    /**
     * Initialize the module.
     */
    public static function init() {
        // Add IDs to headings in content
        add_filter('the_content', [self::class, 'add_heading_ids'], 5);
        
        // Render TOC shortcode
        add_shortcode('toc', [self::class, 'shortcode']);
    }
    
    /**
     * Add IDs to headings that don't have them.
     *
     * @param string $content Post content
     * @return string Modified content
     */
    public static function add_heading_ids($content) {
        if (!is_singular('post') && !is_singular('page')) {
            return $content;
        }
        
        // Reset headings for each post
        self::$headings = [];
        
        // Match all h2-h4 headings
        $content = preg_replace_callback(
            '/<h([2-4])([^>]*)>(.+?)<\/h\1>/i',
            [self::class, 'process_heading'],
            $content
        );
        
        return $content;
    }
    
    /**
     * Process a single heading.
     *
     * @param array $matches Regex matches
     * @return string Heading with ID
     */
    private static function process_heading($matches) {
        $level = $matches[1];
        $attributes = $matches[2];
        $text = $matches[3];
        
        // Check if ID already exists
        if (preg_match('/id=["\']([^"\']+)["\']/', $attributes, $id_match)) {
            $id = $id_match[1];
        } else {
            // Generate ID from text
            $clean_text = wp_strip_all_tags($text);
            $id = sanitize_title($clean_text);
            
            // Ensure unique ID
            $original_id = $id;
            $counter = 1;
            
            while (isset(self::$headings[$id])) {
                $id = $original_id . '-' . $counter;
                $counter++;
            }
            
            // Add ID to attributes
            $attributes .= ' id="' . esc_attr($id) . '"';
        }
        
        // Store heading
        self::$headings[$id] = [
            'level' => (int) $level,
            'text' => wp_strip_all_tags($text),
            'id' => $id,
        ];
        
        // Add anchor class for scroll-margin
        if (strpos($attributes, 'class=') !== false) {
            $attributes = preg_replace('/class=["\']([^"\']+)["\']/', 'class="$1 anchor"', $attributes);
        } else {
            $attributes .= ' class="anchor"';
        }
        
        return sprintf('<h%s%s>%s</h%s>', $level, $attributes, $text, $level);
    }
    
    /**
     * Get extracted headings.
     *
     * @return array
     */
    public static function get_headings() {
        return self::$headings;
    }
    
    /**
     * TOC shortcode.
     *
     * @param array $atts Shortcode attributes
     * @return string TOC HTML
     */
    public static function shortcode($atts = []) {
        $atts = shortcode_atts([
            'title' => __('Table of Contents', 'sage'),
            'min' => 3,
        ], $atts);
        
        $headings = self::get_headings();
        
        if (count($headings) < (int) $atts['min']) {
            return '';
        }
        
        return \Roots\view('components.toc', [
            'content' => '', // Not needed, we have headings
            'headings' => $headings,
            'minHeadings' => (int) $atts['min'],
        ])->render();
    }
    
    /**
     * Check if post has enough headings for TOC.
     *
     * @param int $min Minimum headings required
     * @return bool
     */
    public static function has_toc($min = 3) {
        return count(self::$headings) >= $min;
    }
}

/**
 * ============================================
 * READING PROGRESS MODULE
 * ============================================
 */

class ReadingProgress {
    
    /**
     * Initialize the module.
     */
    public static function init() {
        add_action('increative_after_header', [self::class, 'render']);
        add_action('increative_body_end', [self::class, 'render_back_to_top']);
    }
    
    /**
     * Render reading progress bar.
     */
    public static function render() {
        if (!is_singular('post')) {
            return;
        }
        
        if (\App\is_element_hidden('reading_progress')) {
            return;
        }
        
        self::enqueue_styles();
        ?>
        <div class="reading-progress" aria-hidden="true">
            <div class="reading-progress__bar"></div>
        </div>
        <?php
    }
    
    /**
     * Render back to top button.
     */
    public static function render_back_to_top() {
        ?>
        <button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'sage'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="18 15 12 9 6 15"/>
            </svg>
        </button>
        <?php
    }
    
    /**
     * Enqueue styles.
     */
    private static function enqueue_styles() {
        static $enqueued = false;
        if ($enqueued) return;
        
        add_action('wp_footer', function() {
            ?>
            <script id="reading-progress-js">
            (function() {
                const bar = document.querySelector('.reading-progress__bar');
                const backToTop = document.querySelector('.back-to-top');
                const article = document.querySelector('article, .entry-content, main');
                
                if (!bar || !article) return;
                
                // Reading progress
                const updateProgress = () => {
                    const articleRect = article.getBoundingClientRect();
                    const articleTop = articleRect.top + window.scrollY;
                    const articleHeight = articleRect.height;
                    const windowHeight = window.innerHeight;
                    const scrollY = window.scrollY;
                    
                    const start = articleTop - windowHeight;
                    const end = articleTop + articleHeight - windowHeight;
                    const progress = Math.min(100, Math.max(0, ((scrollY - start) / (end - start)) * 100));
                    
                    bar.style.width = progress + '%';
                };
                
                // Back to top visibility
                const toggleBackToTop = () => {
                    if (backToTop) {
                        backToTop.classList.toggle('is-visible', window.scrollY > 400);
                    }
                };
                
                // Back to top click
                if (backToTop) {
                    backToTop.addEventListener('click', () => {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                }
                
                // Optimized scroll handler
                let ticking = false;
                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        window.requestAnimationFrame(() => {
                            updateProgress();
                            toggleBackToTop();
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, { passive: true });
                
                updateProgress();
            })();
            </script>
            <?php
        }, 100);
        
        $enqueued = true;
    }
}

/**
 * ============================================
 * SOCIAL SHARE MODULE
 * ============================================
 */

class SocialShare {
    
    /**
     * Initialize the module.
     */
    public static function init() {
        // Floating share bar
        add_action('increative_body_end', [self::class, 'render_floating']);
        
        // Inline share (after content)
        add_action('increative_after_post_content', [self::class, 'render_inline'], 15);
    }
    
    /**
     * Render floating share bar.
     */
    public static function render_floating() {
        if (!is_singular('post')) {
            return;
        }
        
        if (\App\is_element_hidden('share_buttons')) {
            return;
        }
        
        self::enqueue_scripts();
        
        echo \Roots\view('components.share', [
            'type' => 'floating',
        ])->render();
    }
    
    /**
     * Render inline share buttons.
     */
    public static function render_inline() {
        if (\App\is_element_hidden('share_buttons')) {
            return;
        }
        
        self::enqueue_scripts();
        
        echo \Roots\view('components.share', [
            'type' => 'inline',
        ])->render();
    }
    
    /**
     * Enqueue share scripts.
     */
    private static function enqueue_scripts() {
        static $enqueued = false;
        if ($enqueued) return;
        
        add_action('wp_footer', function() {
            ?>
            <script id="share-js">
            (function() {
                // Copy link
                document.querySelectorAll('[data-share-copy]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const container = btn.closest('[data-share-url]');
                        const url = container?.dataset.shareUrl || window.location.href;
                        
                        try {
                            await navigator.clipboard.writeText(url);
                            btn.classList.add('is-copied');
                            setTimeout(() => btn.classList.remove('is-copied'), 2000);
                        } catch (err) {
                            console.error('Failed to copy:', err);
                        }
                    });
                });
                
                // Native share
                document.querySelectorAll('[data-share-native]').forEach(btn => {
                    if (!navigator.share) {
                        btn.style.display = 'none';
                        return;
                    }
                    
                    btn.addEventListener('click', async () => {
                        try {
                            await navigator.share({
                                title: btn.dataset.title || document.title,
                                url: btn.dataset.url || window.location.href,
                            });
                        } catch (err) {
                            if (err.name !== 'AbortError') {
                                console.error('Share failed:', err);
                            }
                        }
                    });
                });
                
                // Floating share visibility
                const floatingShare = document.querySelector('.share--floating');
                if (floatingShare) {
                    let ticking = false;
                    window.addEventListener('scroll', () => {
                        if (!ticking) {
                            window.requestAnimationFrame(() => {
                                floatingShare.classList.toggle('is-visible', window.scrollY > 400);
                                ticking = false;
                            });
                            ticking = true;
                        }
                    }, { passive: true });
                }
            })();
            </script>
            <?php
        }, 100);
        
        $enqueued = true;
    }
}

/**
 * ============================================
 * MEGA MENU MODULE
 * ============================================
 */

class MegaMenu {
    
    /**
     * Initialize the module.
     */
    public static function init() {
        // Add mega menu classes to nav items
        add_filter('nav_menu_css_class', [self::class, 'add_menu_item_classes'], 10, 4);
        
        // Add mega menu content
        add_filter('walker_nav_menu_start_el', [self::class, 'add_mega_content'], 10, 4);
        
        // Register meta box for menu items
        add_action('wp_nav_menu_item_custom_fields', [self::class, 'add_menu_item_fields'], 10, 4);
        
        // Save menu item meta
        add_action('wp_update_nav_menu_item', [self::class, 'save_menu_item_meta'], 10, 3);
    }
    
    /**
     * Add mega menu classes to menu items.
     */
    public static function add_menu_item_classes($classes, $item, $args, $depth) {
        if ($depth === 0 && get_post_meta($item->ID, '_mega_menu', true)) {
            $classes[] = 'menu-item-has-mega';
        }
        
        return $classes;
    }
    
    /**
     * Add mega menu content after menu item.
     */
    public static function add_mega_content($item_output, $item, $depth, $args) {
        if ($depth !== 0) {
            return $item_output;
        }
        
        $mega_enabled = get_post_meta($item->ID, '_mega_menu', true);
        
        if (!$mega_enabled) {
            return $item_output;
        }
        
        $mega_columns = get_post_meta($item->ID, '_mega_columns', true) ?: 3;
        $mega_content = get_post_meta($item->ID, '_mega_content', true) ?: '';
        
        // Build mega menu content
        $mega_html = '<div class="mega-menu" data-columns="' . esc_attr($mega_columns) . '">';
        $mega_html .= '<div class="mega-menu__inner">';
        
        if ($mega_content) {
            $mega_html .= '<div class="mega-menu__content">' . wp_kses_post($mega_content) . '</div>';
        }
        
        $mega_html .= '</div></div>';
        
        return $item_output . $mega_html;
    }
    
    /**
     * Add custom fields to menu items.
     */
    public static function add_menu_item_fields($item_id, $item, $depth, $args) {
        if ($depth !== 0) {
            return;
        }
        
        $mega_enabled = get_post_meta($item_id, '_mega_menu', true);
        $mega_columns = get_post_meta($item_id, '_mega_columns', true) ?: 3;
        ?>
        <p class="field-mega-menu description description-wide">
            <label>
                <input 
                    type="checkbox" 
                    name="menu-item-mega[<?php echo $item_id; ?>]" 
                    value="1"
                    <?php checked($mega_enabled, 1); ?>
                >
                <?php _e('Enable Mega Menu', 'sage'); ?>
            </label>
        </p>
        <p class="field-mega-columns description description-wide">
            <label>
                <?php _e('Mega Menu Columns', 'sage'); ?>
                <select name="menu-item-mega-columns[<?php echo $item_id; ?>]">
                    <?php for ($i = 2; $i <= 5; $i++) : ?>
                        <option value="<?php echo $i; ?>" <?php selected($mega_columns, $i); ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </label>
        </p>
        <?php
    }
    
    /**
     * Save menu item meta.
     */
    public static function save_menu_item_meta($menu_id, $menu_item_db_id, $args) {
        $mega_enabled = isset($_POST['menu-item-mega'][$menu_item_db_id]) ? 1 : 0;
        update_post_meta($menu_item_db_id, '_mega_menu', $mega_enabled);
        
        if (isset($_POST['menu-item-mega-columns'][$menu_item_db_id])) {
            $columns = (int) $_POST['menu-item-mega-columns'][$menu_item_db_id];
            update_post_meta($menu_item_db_id, '_mega_columns', $columns);
        }
    }
}

/**
 * Initialize all new modules.
 */
add_action('init', function() {
    $modules = apply_filters('increative_enabled_modules', [
        'table_of_contents' => true,
        'reading_progress' => true,
        'social_share' => true,
        'mega_menu' => true,
    ]);
    
    if (!empty($modules['table_of_contents'])) {
        TableOfContents::init();
    }
    
    if (!empty($modules['reading_progress'])) {
        ReadingProgress::init();
    }
    
    if (!empty($modules['social_share'])) {
        SocialShare::init();
    }
    
    if (!empty($modules['mega_menu'])) {
        MegaMenu::init();
    }
}, 20);
