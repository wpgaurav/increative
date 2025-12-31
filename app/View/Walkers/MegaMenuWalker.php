<?php

/**
 * Mega Menu Walker
 * 
 * Custom navigation walker that supports mega menu layouts.
 */

namespace App\View\Walkers;

use Walker_Nav_Menu;

class MegaMenuWalker extends Walker_Nav_Menu {
    
    /**
     * Current menu item depth.
     */
    private $current_depth = 0;
    
    /**
     * Is current item a mega menu?
     */
    private $is_mega = false;
    
    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        if ($depth === 0 && $this->is_mega) {
            $output .= "\n{$indent}<div class=\"mega-menu\">\n";
            $output .= "{$indent}\t<div class=\"mega-menu__container\">\n";
            $output .= "{$indent}\t\t<ul class=\"mega-menu__list\">\n";
        } elseif ($depth === 0) {
            $output .= "\n{$indent}<ul class=\"dropdown-menu\">\n";
        } else {
            $output .= "\n{$indent}<ul class=\"dropdown-menu__sub\">\n";
        }
    }
    
    /**
     * Ends the list of after the elements are added.
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        if ($depth === 0 && $this->is_mega) {
            $output .= "{$indent}\t\t</ul>\n";
            $output .= "{$indent}\t</div>\n";
            $output .= "{$indent}</div>\n";
        } else {
            $output .= "{$indent}</ul>\n";
        }
    }
    
    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $this->current_depth = $depth;
        
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Check for mega menu class
        if ($depth === 0 && in_array('mega-menu', $classes)) {
            $this->is_mega = true;
            $classes[] = 'has-mega-menu';
        } elseif ($depth === 0) {
            $this->is_mega = false;
        }
        
        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);
        
        // Add active class
        if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
            $classes[] = 'is-active';
        }
        
        // Item classes based on depth
        if ($depth === 0) {
            $classes[] = 'nav-item';
        } elseif ($this->is_mega && $depth === 1) {
            $classes[] = 'mega-menu__column';
        } else {
            $classes[] = 'dropdown-item';
        }
        
        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= $indent . '<li' . $class_names . '>';
        
        // Build the link
        $atts = [];
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target) ? $item->target : '';
        $atts['rel']    = ! empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = ! empty($item->url) ? $item->url : '';
        
        // Link class based on depth
        if ($depth === 0) {
            $atts['class'] = 'nav-link';
            if (in_array('current-menu-item', $classes)) {
                $atts['class'] .= ' nav-link--active';
            }
        } elseif ($this->is_mega && $depth === 1) {
            $atts['class'] = 'mega-menu__heading';
        } else {
            $atts['class'] = 'dropdown-link';
        }
        
        if ($has_children && $depth === 0) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
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
        
        // Get description for mega menu items
        $description = '';
        if ($this->is_mega && $depth > 0 && ! empty($item->description)) {
            $description = '<span class="dropdown-link__desc">' . esc_html($item->description) . '</span>';
        }
        
        $item_output = $args->before ?? '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= ($args->link_before ?? '') . $title . ($args->link_after ?? '');
        
        // Add dropdown arrow for items with children
        if ($has_children && $depth === 0) {
            $item_output .= '<svg class="nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= $description;
        $item_output .= $args->after ?? '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}
