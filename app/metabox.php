<?php

/**
 * Theme Metabox
 * 
 * Adds options to hide/show theme elements per post, page, or CPT.
 */

namespace App;

/**
 * Register the metabox.
 */
add_action('add_meta_boxes', function () {
    $post_types = apply_filters('increative_metabox_post_types', ['post', 'page']);
    
    add_meta_box(
        'increative_layout_options',
        __('Layout Options', 'sage'),
        __NAMESPACE__ . '\\render_layout_metabox',
        $post_types,
        'side',
        'default'
    );
});

/**
 * Render the metabox content.
 */
function render_layout_metabox($post) {
    wp_nonce_field('increative_layout_options', 'increative_layout_nonce');
    
    $options = get_post_meta($post->ID, '_increative_layout', true);
    $options = wp_parse_args($options ?: [], [
        'hide_header' => false,
        'hide_footer' => false,
        'hide_breadcrumbs' => false,
        'hide_title' => false,
        'hide_featured_image' => false,
        'hide_meta' => false,
        'hide_author_box' => false,
        'hide_related_posts' => false,
        'hide_comments' => false,
        'hide_sidebar' => false,
        'full_width' => false,
        'transparent_header' => false,
    ]);
    
    $fields = [
        'hide_header' => __('Hide Header', 'sage'),
        'hide_footer' => __('Hide Footer', 'sage'),
        'hide_breadcrumbs' => __('Hide Breadcrumbs', 'sage'),
        'hide_title' => __('Hide Title', 'sage'),
        'hide_featured_image' => __('Hide Featured Image', 'sage'),
        'hide_meta' => __('Hide Post Meta', 'sage'),
        'hide_author_box' => __('Hide Author Box', 'sage'),
        'hide_related_posts' => __('Hide Related Posts', 'sage'),
        'hide_comments' => __('Hide Comments', 'sage'),
        'hide_sidebar' => __('Hide Sidebar', 'sage'),
        'full_width' => __('Full Width Layout', 'sage'),
        'transparent_header' => __('Transparent Header', 'sage'),
    ];
    ?>
    <div class="increative-metabox">
        <style>
            .increative-metabox label {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 6px 0;
                cursor: pointer;
            }
            .increative-metabox label:hover {
                color: #2271b1;
            }
            .increative-metabox hr {
                margin: 10px 0;
                border: none;
                border-top: 1px solid #ddd;
            }
        </style>
        
        <p><strong><?php _e('Hide Elements', 'sage'); ?></strong></p>
        
        <?php foreach ($fields as $key => $label) : ?>
            <?php if ($key === 'full_width') : ?>
                <hr>
                <p><strong><?php _e('Layout Options', 'sage'); ?></strong></p>
            <?php endif; ?>
            <label>
                <input 
                    type="checkbox" 
                    name="increative_layout[<?php echo esc_attr($key); ?>]" 
                    value="1"
                    <?php checked($options[$key], true); ?>
                >
                <?php echo esc_html($label); ?>
            </label>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Save the metabox data.
 */
add_action('save_post', function ($post_id) {
    // Verify nonce
    if (!isset($_POST['increative_layout_nonce']) || 
        !wp_verify_nonce($_POST['increative_layout_nonce'], 'increative_layout_options')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save options
    $options = [];
    $fields = [
        'hide_header', 'hide_footer', 'hide_breadcrumbs', 'hide_title',
        'hide_featured_image', 'hide_meta', 'hide_author_box', 
        'hide_related_posts', 'hide_comments', 'hide_sidebar',
        'full_width', 'transparent_header'
    ];

    foreach ($fields as $field) {
        $options[$field] = isset($_POST['increative_layout'][$field]);
    }

    update_post_meta($post_id, '_increative_layout', $options);
});

/**
 * Get layout option for current post.
 *
 * @param string $option Option key
 * @param mixed $default Default value
 * @return mixed
 */
function get_layout_option($option, $default = false) {
    $post_id = get_the_ID();
    if (!$post_id) return $default;
    
    $options = get_post_meta($post_id, '_increative_layout', true);
    
    if (!$options || !isset($options[$option])) {
        return $default;
    }
    
    return $options[$option];
}

/**
 * Check if element should be hidden.
 *
 * @param string $element Element name
 * @return bool
 */
function is_element_hidden($element) {
    return (bool) get_layout_option('hide_' . $element, false);
}

/**
 * Check if using full width layout.
 *
 * @return bool
 */
function is_full_width() {
    return (bool) get_layout_option('full_width', false);
}

/**
 * Check if header should be transparent.
 *
 * @return bool
 */
function is_transparent_header() {
    return (bool) get_layout_option('transparent_header', false);
}

/**
 * Add body classes based on layout options.
 */
add_filter('body_class', function ($classes) {
    if (is_singular()) {
        if (is_full_width()) {
            $classes[] = 'is-full-width';
        }
        if (is_transparent_header()) {
            $classes[] = 'has-transparent-header';
        }
        if (is_element_hidden('sidebar')) {
            $classes[] = 'no-sidebar';
        }
    }
    
    return $classes;
});
