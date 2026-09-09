<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\Auth;

class ThemeService
{
    /**
     * Get the active theme for current request.
     */
    public static function getActiveTheme(): Theme
    {
        if (Auth::check() && Auth::user()->theme_id) {
            $userTheme = Auth::user()->theme;
            if ($userTheme && $userTheme->is_active) {
                return $userTheme;
            }
        }

        // Fallback to Admin Default Theme
        return Theme::getDefaultTheme() ?? static::getFallbackDefaultTheme();
    }

    /**
     * Generate inline CSS string for active theme.
     */
    public static function renderCssVariables(): string
    {
        $theme = static::getActiveTheme();
        return $theme->toCssVariables();
    }

    /**
     * Fallback Theme instance in case DB has no records.
     */
    public static function getFallbackDefaultTheme(): Theme
    {
        $theme = new Theme();
        $theme->name = 'Default (Original)';
        $theme->slug = 'default-original';
        $theme->type = 'light';
        $theme->is_default = true;
        $theme->is_active = true;
        $theme->colors = static::getDefaultColorPalette();
        return $theme;
    }

    /**
     * Exact hex palette for Default (Original) theme.
     */
    public static function getDefaultColorPalette(): array
    {
        return [
            // Base
            'bg_page' => '#f8fafc',
            'bg_surface' => '#ffffff',
            'border_color' => '#e2e8f0',

            // Text
            'text_primary' => '#0f172a',
            'text_secondary' => '#64748b',
            'text_heading' => '#0f172a',
            'text_link' => '#0284c7',
            'text_link_hover' => '#0369a1',

            // Navbar / Header
            'navbar_bg' => '#ffffff',
            'navbar_text' => '#0f172a',
            'navbar_active' => '#0284c7',

            // Buttons
            'btn_primary_bg' => '#0284c7',
            'btn_primary_text' => '#ffffff',
            'btn_primary_hover' => '#0369a1',
            'btn_secondary_bg' => '#ffffff',
            'btn_secondary_text' => '#334155',
            'btn_secondary_border' => '#cbd5e1',

            // Cards
            'card_bg' => '#ffffff',
            'card_border' => '#e2e8f0',
            'card_title' => '#0f172a',
            'card_body' => '#334155',

            // Table
            'table_header_bg' => '#f8fafc',
            'table_header_text' => '#475569',
            'table_row_bg' => '#ffffff',
            'table_row_alt' => '#f1f5f9',
            'table_row_text' => '#0f172a',
            'table_border' => '#e2e8f0',

            // Forms / Inputs
            'input_bg' => '#ffffff',
            'input_border' => '#cbd5e1',
            'input_focus' => '#0284c7',
            'input_placeholder' => '#94a3b8',

            // Badges
            'badge_success_bg' => '#ecfdf5',
            'badge_success_text' => '#065f46',
            'badge_warning_bg' => '#fffbeb',
            'badge_warning_text' => '#92400e',
            'badge_error_bg' => '#fef2f2',
            'badge_error_text' => '#991b1b',
            'badge_info_bg' => '#f0f9ff',
            'badge_info_text' => '#075985',

            // Footer
            'footer_bg' => '#0f172a',
            'footer_text' => '#94a3b8',
            'footer_link_hover' => '#38bdf8',

            // Misc
            'modal_bg' => '#ffffff',
            'modal_overlay' => 'rgba(15, 23, 42, 0.6)',
            'icon_color' => '#0284c7',
        ];
    }
}
