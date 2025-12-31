<?php

namespace App\View\Walkers;

use Walker_Nav_Menu;

/**
 * Custom Navigation Walker for primary navigation
 */
class NavWalker extends Walker_Nav_Menu
{
    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"primary-nav__dropdown\">\n";
    }

    /**
     * Ends the list of after the elements are added.
     */
    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Add active class
        if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
            $classes[] = 'is-active';
        }

        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);

        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id_attr = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id_attr = $id_attr ? ' id="' . esc_attr($id_attr) . '"' : '';

        $output .= $indent . '<li' . $id_attr . $class_names . '>';

        // Build the link
        $atts = [];
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target) ? $item->target : '';
        $atts['rel']    = ! empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = ! empty($item->url) ? $item->url : '';
        
        // Add link class based on depth
        if ($depth === 0) {
            $atts['class'] = 'primary-nav__link';
            if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
                $atts['class'] .= ' primary-nav__link--active';
            }
        } else {
            $atts['class'] = 'primary-nav__dropdown-link';
        }

        // Add aria-current for current item
        if (in_array('current-menu-item', $classes)) {
            $atts['aria-current'] = 'page';
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before ?? '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= ($args->link_before ?? '') . $title . ($args->link_after ?? '');
        
        // Add dropdown arrow for items with children
        if ($has_children && $depth === 0) {
            $item_output .= ' <svg class="w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= "</li>\n";
    }
}
