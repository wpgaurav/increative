<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }

    /**
     * Retrieve the site description.
     */
    public function siteDescription(): string
    {
        return get_bloginfo('description', 'display');
    }

    /**
     * Retrieve current year for copyright.
     */
    public function currentYear(): string
    {
        return date('Y');
    }

    /**
     * Get the custom font family if set.
     */
    public function customFont(): ?string
    {
        return get_theme_mod('increative_custom_font', null);
    }

    /**
     * Get social links.
     */
    public function socialLinks(): array
    {
        return [
            'twitter' => get_theme_mod('increative_twitter', ''),
            'linkedin' => get_theme_mod('increative_linkedin', ''),
            'github' => get_theme_mod('increative_github', ''),
            'youtube' => get_theme_mod('increative_youtube', ''),
            'instagram' => get_theme_mod('increative_instagram', ''),
        ];
    }
}
