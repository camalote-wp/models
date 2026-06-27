<?php

namespace CamaloteWP\Models\Interfaces;

interface AdminPage
{
    /**
     * Return the page configuration.
     *
     * Required keys:
     *   - parent_slug:  string The parent menu slug (for submenu pages)
     *   - page_title:   string The page title
     *   - menu_slug:    string The menu slug
     *   - screen_id:    string The WP screen ID for asset loading
     *
     * Optional keys:
     *   - capability:   string Required capability (default 'manage_options')
     *   - icon:         string Menu icon URL (menu page only)
     *   - position:     int|null Menu position (menu page only)
     *
     * @return array<string, mixed>
     */
    public function get_page_config(): array;

    /**
     * Return the asset configuration.
     *
     * Required keys:
     *   - handle:      string Script/style handle
     *   - asset_url:   string Base URL to the assets directory
     *   - asset_dir:   string Base path to the assets directory
     *   - script:      string Filename of the script (relative to asset_url)
     *   - style:       string Filename of the stylesheet (relative to asset_url)
     *   - deps_file:   string Filename of the .asset.php dependency file (relative to asset_dir)
     *
     * @return array<string, mixed>
     */
    public function get_asset_config(): array;

    public function register_submenu_page(): void;

    public function register_menu_page(): void;

    public function render_page(): void;
}
