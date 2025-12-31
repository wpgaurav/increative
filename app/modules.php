<?php

/**
 * Modular Features System
 * 
 * Provides modular components that can be enabled/disabled:
 * - Related Posts
 * - Popups
 * - Callouts
 * - Floating Bars
 * - Mega Menu
 */

namespace App\Modules;

/**
 * ============================================
 * RELATED POSTS MODULE
 * ============================================
 */
class RelatedPosts {
    
    /**
     * Initialize the module.
     */
    public static function init() {
        add_action('increative_after_post_content', [self::class, 'render'], 20);
    }
    
    /**
     * Render related posts.
     */
    public static function render() {
        if (\App\is_element_hidden('related_posts')) {
            return;
        }
        
        if (!is_singular('post')) {
            return;
        }
        
        $post_id = get_the_ID();
        $categories = wp_get_post_categories($post_id);
        $tags = wp_get_post_tags($post_id, ['fields' => 'ids']);
        
        $args = apply_filters('increative_related_posts_args', [
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => [$post_id],
            'orderby' => 'rand',
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $categories,
                ],
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $tags,
                ],
            ],
        ]);
        
        $query = new \WP_Query($args);
        
        if (!$query->have_posts()) {
            return;
        }
        
        // Enqueue related posts CSS
        self::enqueue_styles();
        
        echo '<section class="related-posts">';
        echo '<h2 class="related-posts__title">' . esc_html__('Related Posts', 'sage') . '</h2>';
        echo '<div class="related-posts__grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            self::render_card();
        }
        
        wp_reset_postdata();
        
        echo '</div>';
        echo '</section>';
    }
    
    /**
     * Render a single related post card.
     */
    private static function render_card() {
        ?>
        <article class="related-card">
            <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" class="related-card__image">
                    <?php the_post_thumbnail('thumbnail'); ?>
                </a>
            <?php endif; ?>
            <div class="related-card__content">
                <h3 class="related-card__title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <time class="related-card__date" datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </div>
        </article>
        <?php
    }
    
    /**
     * Enqueue module styles.
     */
    public static function enqueue_styles() {
        static $enqueued = false;
        if ($enqueued) return;
        
        add_action('wp_footer', function() {
            ?>
            <style id="related-posts-css">
                .related-posts { margin-top: var(--space-xl); padding-top: var(--space-l); border-top: 1px solid var(--color-bordered); }
                .related-posts__title { font-size: var(--fs-xl); margin-bottom: var(--space-m); }
                .related-posts__grid { display: grid; gap: var(--space-m); }
                @media (min-width: 640px) { .related-posts__grid { grid-template-columns: repeat(3, 1fr); } }
                .related-card { display: flex; flex-direction: column; gap: var(--space-xs); }
                .related-card__image { border-radius: var(--radius-m); overflow: hidden; }
                .related-card__image img { width: 100%; height: auto; transition: transform var(--transition-duration); }
                .related-card__image:hover img { transform: scale(1.05); }
                .related-card__title { font-size: var(--fs-s); font-weight: 600; line-height: 1.3; }
                .related-card__title a { color: var(--color-text); text-decoration: none; }
                .related-card__title a:hover { color: var(--color-primary); }
                .related-card__date { font-size: var(--fs-xs); color: var(--color-text-sec); }
            </style>
            <?php
        }, 5);
        
        $enqueued = true;
    }
}

/**
 * ============================================
 * POPUP MODULE
 * ============================================
 */
class Popup {
    
    private static $popups = [];
    
    /**
     * Initialize the module.
     */
    public static function init() {
        add_action('increative_after_footer', [self::class, 'render_all']);
    }
    
    /**
     * Register a popup.
     *
     * @param string $id Unique popup ID
     * @param array $args Popup arguments
     */
    public static function register($id, $args = []) {
        self::$popups[$id] = wp_parse_args($args, [
            'title' => '',
            'content' => '',
            'trigger' => 'time', // time, scroll, exit, click
            'delay' => 5000, // ms for time trigger
            'scroll_percent' => 50, // for scroll trigger
            'selector' => '', // for click trigger
            'show_once' => true,
            'cookie_days' => 7,
            'size' => 'medium', // small, medium, large
            'position' => 'center', // center, bottom-right, bottom-left
        ]);
    }
    
    /**
     * Render all registered popups.
     */
    public static function render_all() {
        if (empty(self::$popups)) {
            return;
        }
        
        self::enqueue_styles();
        self::enqueue_scripts();
        
        foreach (self::$popups as $id => $popup) {
            self::render($id, $popup);
        }
    }
    
    /**
     * Render a single popup.
     */
    private static function render($id, $popup) {
        $classes = ['popup', 'popup--' . $popup['size'], 'popup--' . $popup['position']];
        ?>
        <div 
            id="popup-<?php echo esc_attr($id); ?>" 
            class="<?php echo esc_attr(implode(' ', $classes)); ?>"
            data-trigger="<?php echo esc_attr($popup['trigger']); ?>"
            data-delay="<?php echo esc_attr($popup['delay']); ?>"
            data-scroll="<?php echo esc_attr($popup['scroll_percent']); ?>"
            data-selector="<?php echo esc_attr($popup['selector']); ?>"
            data-once="<?php echo $popup['show_once'] ? '1' : '0'; ?>"
            data-cookie="<?php echo esc_attr($popup['cookie_days']); ?>"
            role="dialog"
            aria-modal="true"
            aria-labelledby="popup-<?php echo esc_attr($id); ?>-title"
            hidden
        >
            <div class="popup__backdrop"></div>
            <div class="popup__container">
                <button class="popup__close" aria-label="<?php esc_attr_e('Close popup', 'sage'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
                <?php if ($popup['title']) : ?>
                    <h2 id="popup-<?php echo esc_attr($id); ?>-title" class="popup__title">
                        <?php echo esc_html($popup['title']); ?>
                    </h2>
                <?php endif; ?>
                <div class="popup__content">
                    <?php echo wp_kses_post($popup['content']); ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Enqueue popup styles.
     */
    private static function enqueue_styles() {
        add_action('wp_footer', function() {
            ?>
            <style id="popup-css">
                .popup { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: var(--space-m); opacity: 0; visibility: hidden; transition: opacity var(--transition-duration), visibility var(--transition-duration); }
                .popup[hidden] { display: none; }
                .popup.is-active { opacity: 1; visibility: visible; }
                .popup__backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
                .popup__container { position: relative; background: var(--color-white); border-radius: var(--radius-xl); padding: var(--space-l); max-height: 90vh; overflow-y: auto; box-shadow: var(--main-shadow); animation: popupSlide 0.3s ease; }
                @keyframes popupSlide { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
                .popup--small .popup__container { max-width: 400px; }
                .popup--medium .popup__container { max-width: 560px; }
                .popup--large .popup__container { max-width: 720px; }
                .popup--bottom-right { align-items: flex-end; justify-content: flex-end; }
                .popup--bottom-left { align-items: flex-end; justify-content: flex-start; }
                .popup__close { position: absolute; top: var(--space-s); right: var(--space-s); background: none; border: none; cursor: pointer; color: var(--color-text-sec); padding: var(--space-xs); border-radius: var(--radius-s); transition: color var(--transition-fast), background var(--transition-fast); }
                .popup__close:hover { color: var(--color-text); background: var(--color-offwhite); }
                .popup__title { font-size: var(--fs-xl); margin-bottom: var(--space-m); padding-right: var(--space-l); }
                .popup__content { font-size: var(--fs-base); }
            </style>
            <?php
        }, 5);
    }
    
    /**
     * Enqueue popup scripts.
     */
    private static function enqueue_scripts() {
        add_action('wp_footer', function() {
            ?>
            <script id="popup-js">
            (function() {
                document.querySelectorAll('.popup').forEach(popup => {
                    const id = popup.id;
                    const trigger = popup.dataset.trigger;
                    const delay = parseInt(popup.dataset.delay) || 5000;
                    const scrollPercent = parseInt(popup.dataset.scroll) || 50;
                    const selector = popup.dataset.selector;
                    const showOnce = popup.dataset.once === '1';
                    const cookieDays = parseInt(popup.dataset.cookie) || 7;
                    
                    // Check cookie
                    if (showOnce && document.cookie.includes(id + '=shown')) return;
                    
                    const show = () => {
                        popup.hidden = false;
                        requestAnimationFrame(() => popup.classList.add('is-active'));
                        if (showOnce) {
                            document.cookie = id + '=shown;path=/;max-age=' + (cookieDays * 86400);
                        }
                    };
                    
                    const hide = () => {
                        popup.classList.remove('is-active');
                        setTimeout(() => popup.hidden = true, 300);
                    };
                    
                    // Triggers
                    if (trigger === 'time') {
                        setTimeout(show, delay);
                    } else if (trigger === 'scroll') {
                        let shown = false;
                        window.addEventListener('scroll', () => {
                            if (shown) return;
                            const scrolled = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
                            if (scrolled >= scrollPercent) { show(); shown = true; }
                        });
                    } else if (trigger === 'exit') {
                        document.addEventListener('mouseout', e => {
                            if (e.clientY < 10 && !popup.classList.contains('is-active')) show();
                        }, { once: true });
                    } else if (trigger === 'click' && selector) {
                        document.querySelectorAll(selector).forEach(el => el.addEventListener('click', e => { e.preventDefault(); show(); }));
                    }
                    
                    // Close handlers
                    popup.querySelector('.popup__close')?.addEventListener('click', hide);
                    popup.querySelector('.popup__backdrop')?.addEventListener('click', hide);
                    document.addEventListener('keydown', e => { if (e.key === 'Escape') hide(); });
                });
            })();
            </script>
            <?php
        }, 100);
    }
}

/**
 * ============================================
 * CALLOUT MODULE
 * ============================================
 */
class Callout {
    
    /**
     * Render a callout.
     *
     * @param array $args Callout arguments
     * @return string HTML output
     */
    public static function render($args = []) {
        $args = wp_parse_args($args, [
            'type' => 'info', // info, success, warning, error, tip
            'title' => '',
            'content' => '',
            'icon' => true,
            'dismissible' => false,
        ]);
        
        self::enqueue_styles();
        
        $classes = ['callout', 'callout--' . $args['type']];
        if ($args['dismissible']) $classes[] = 'callout--dismissible';
        
        $icons = [
            'info' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>',
            'success' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/></svg>',
            'warning' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            'error' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            'tip' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><circle cx="12" cy="12" r="10"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        ];
        
        ob_start();
        ?>
        <div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
            <?php if ($args['icon']) : ?>
                <div class="callout__icon"><?php echo $icons[$args['type']] ?? $icons['info']; ?></div>
            <?php endif; ?>
            <div class="callout__body">
                <?php if ($args['title']) : ?>
                    <div class="callout__title"><?php echo esc_html($args['title']); ?></div>
                <?php endif; ?>
                <div class="callout__content"><?php echo wp_kses_post($args['content']); ?></div>
            </div>
            <?php if ($args['dismissible']) : ?>
                <button class="callout__dismiss" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Enqueue callout styles.
     */
    private static function enqueue_styles() {
        static $enqueued = false;
        if ($enqueued) return;
        
        add_action('wp_footer', function() {
            ?>
            <style id="callout-css">
                .callout { display: flex; gap: var(--space-s); padding: var(--space-m); border-radius: var(--radius-m); margin: var(--space-m) 0; }
                .callout--info { background: var(--color-aliceblue); border-left: 4px solid var(--color-action); }
                .callout--success { background: var(--color-green-card); border-left: 4px solid var(--color-subtle-green); }
                .callout--warning { background: var(--color-shade-yellow); border-left: 4px solid var(--color-accent); }
                .callout--error { background: var(--color-red-card); border-left: 4px solid var(--color-primary); }
                .callout--tip { background: var(--color-almond); border-left: 4px solid var(--color-secondary); }
                .callout__icon { flex-shrink: 0; }
                .callout--info .callout__icon { color: var(--color-action); }
                .callout--success .callout__icon { color: var(--color-subtle-green); }
                .callout--warning .callout__icon { color: var(--color-accent); }
                .callout--error .callout__icon { color: var(--color-primary); }
                .callout--tip .callout__icon { color: var(--color-secondary); }
                .callout__body { flex: 1; }
                .callout__title { font-weight: 600; margin-bottom: var(--space-xs); }
                .callout__content { font-size: var(--fs-s); }
                .callout__dismiss { align-self: flex-start; background: none; border: none; cursor: pointer; opacity: 0.5; transition: opacity var(--transition-fast); }
                .callout__dismiss:hover { opacity: 1; }
            </style>
            <?php
        }, 5);
        
        $enqueued = true;
    }
}

/**
 * ============================================
 * FLOATING BAR MODULE
 * ============================================
 */
class FloatingBar {
    
    private static $bars = [];
    
    /**
     * Initialize the module.
     */
    public static function init() {
        add_action('increative_before_header', [self::class, 'render_top_bars']);
        add_action('increative_after_footer', [self::class, 'render_bottom_bars']);
    }
    
    /**
     * Register a floating bar.
     *
     * @param string $id Unique bar ID
     * @param array $args Bar arguments
     */
    public static function register($id, $args = []) {
        self::$bars[$id] = wp_parse_args($args, [
            'content' => '',
            'position' => 'top', // top, bottom
            'style' => 'info', // info, promo, warning, dark
            'sticky' => true,
            'dismissible' => true,
            'cookie_days' => 1,
            'button_text' => '',
            'button_url' => '',
        ]);
    }
    
    /**
     * Render top bars.
     */
    public static function render_top_bars() {
        self::render_bars('top');
    }
    
    /**
     * Render bottom bars.
     */
    public static function render_bottom_bars() {
        self::render_bars('bottom');
    }
    
    /**
     * Render bars by position.
     */
    private static function render_bars($position) {
        $bars = array_filter(self::$bars, fn($bar) => $bar['position'] === $position);
        
        if (empty($bars)) return;
        
        self::enqueue_styles();
        
        foreach ($bars as $id => $bar) {
            // Check cookie
            if ($bar['dismissible'] && isset($_COOKIE['floating_bar_' . $id])) {
                continue;
            }
            
            self::render_bar($id, $bar);
        }
    }
    
    /**
     * Render a single bar.
     */
    private static function render_bar($id, $bar) {
        $classes = [
            'floating-bar',
            'floating-bar--' . $bar['position'],
            'floating-bar--' . $bar['style'],
        ];
        if ($bar['sticky']) $classes[] = 'floating-bar--sticky';
        ?>
        <div id="floating-bar-<?php echo esc_attr($id); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>" data-cookie="<?php echo esc_attr($bar['cookie_days']); ?>">
            <div class="floating-bar__container">
                <div class="floating-bar__content">
                    <?php echo wp_kses_post($bar['content']); ?>
                </div>
                <?php if ($bar['button_text'] && $bar['button_url']) : ?>
                    <a href="<?php echo esc_url($bar['button_url']); ?>" class="floating-bar__button">
                        <?php echo esc_html($bar['button_text']); ?>
                    </a>
                <?php endif; ?>
                <?php if ($bar['dismissible']) : ?>
                    <button class="floating-bar__dismiss" aria-label="<?php esc_attr_e('Dismiss', 'sage'); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Enqueue bar styles.
     */
    private static function enqueue_styles() {
        static $enqueued = false;
        if ($enqueued) return;
        
        add_action('wp_footer', function() {
            ?>
            <style id="floating-bar-css">
                .floating-bar { padding: var(--space-s) 0; }
                .floating-bar--sticky.floating-bar--top { position: sticky; top: 0; z-index: 101; }
                .floating-bar--sticky.floating-bar--bottom { position: fixed; bottom: 0; left: 0; right: 0; z-index: 101; }
                .floating-bar--info { background: var(--color-action); color: var(--color-white); }
                .floating-bar--promo { background: var(--color-accent-new); color: var(--color-text); }
                .floating-bar--warning { background: var(--color-primary); color: var(--color-white); }
                .floating-bar--dark { background: var(--color-bg-highlight); color: var(--color-white); }
                .floating-bar__container { display: flex; align-items: center; justify-content: center; gap: var(--space-m); max-width: var(--max); margin: 0 auto; padding: 0 var(--gutter); }
                .floating-bar__content { font-size: var(--fs-s); font-weight: 500; }
                .floating-bar__button { font-size: var(--fs-xs); font-weight: 600; padding: var(--space-xs) var(--space-s); background: rgba(255,255,255,0.2); border-radius: var(--radius-s); color: inherit; text-decoration: none; transition: background var(--transition-fast); }
                .floating-bar__button:hover { background: rgba(255,255,255,0.3); color: inherit; }
                .floating-bar__dismiss { background: none; border: none; cursor: pointer; color: inherit; opacity: 0.7; transition: opacity var(--transition-fast); }
                .floating-bar__dismiss:hover { opacity: 1; }
            </style>
            <script>
            document.querySelectorAll('.floating-bar__dismiss').forEach(btn => {
                btn.addEventListener('click', () => {
                    const bar = btn.closest('.floating-bar');
                    const days = parseInt(bar.dataset.cookie) || 1;
                    document.cookie = bar.id + '=dismissed;path=/;max-age=' + (days * 86400);
                    bar.remove();
                });
            });
            </script>
            <?php
        }, 5);
        
        $enqueued = true;
    }
}

/**
 * ============================================
 * MODULE LOADER
 * ============================================
 */

/**
 * Initialize all modules.
 */
function init_modules() {
    // Check which modules are enabled
    $modules = apply_filters('increative_enabled_modules', [
        'related_posts' => true,
        'popups' => true,
        'callouts' => true,
        'floating_bars' => true,
    ]);
    
    if ($modules['related_posts']) {
        RelatedPosts::init();
    }
    
    if ($modules['popups']) {
        Popup::init();
    }
    
    if ($modules['floating_bars']) {
        FloatingBar::init();
    }
}

add_action('init', __NAMESPACE__ . '\\init_modules');
