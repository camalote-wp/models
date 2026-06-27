<?php

namespace CamaloteWP\Models\Abstracts;

use CamaloteWP\Models\Interfaces\AdminPage;
use CamaloteWP\Models\Interfaces\Hookable;

abstract class AbstractModelAdminPage implements AdminPage, Hookable
{
    protected string $model_name;

    public function get_hooks(): array
    {
        return [];
    }

    /**
     * Define the page configuration.
     *
     * Required keys: parent_slug, page_title, menu_slug, screen_id
     * Optional keys: capability, icon, position
     */
    abstract public function get_page_config(): array;

    /**
     * Define the asset configuration.
     *
     * Required keys: handle, asset_url, asset_dir, script, style, deps_file
     */
    abstract public function get_asset_config(): array;

    abstract public function render_page(): void;

    public function register_submenu_page(): void
    {
        \add_submenu_page(
            $this->get_page_config()['parent_slug'],
            $this->get_page_config()['page_title'],
            $this->get_page_config()['page_title'],
            $this->get_page_config()['capability'] ?? 'manage_options',
            $this->get_page_config()['menu_slug'],
            [$this, 'render_page']
        );
    }

    public function register_menu_page(): void
    {
        \add_menu_page(
            $this->get_page_config()['page_title'],
            $this->get_page_config()['page_title'],
            $this->get_page_config()['capability'] ?? 'manage_options',
            $this->get_page_config()['menu_slug'],
            [$this, 'render_page'],
            $this->get_page_config()['icon'] ?? '',
            $this->get_page_config()['position'] ?? null
        );
    }

    public function enqueue_assets(): void
    {
        if (\get_current_screen()->id !== $this->get_page_config()['screen_id']) {
            return;
        }

        $config = $this->get_asset_config();
        $deps_file = $config['asset_dir'].$config['deps_file'];
        $dependencies = [];
        $version = '1.0.0';

        if (\file_exists($deps_file)) {
            $asset = require $deps_file;
            $dependencies = $asset['dependencies'] ?? [];
            $version = $asset['version'] ?? '1.0.0';
        }

        \wp_enqueue_script(
            $config['handle'],
            $config['asset_url'].$config['script'],
            $dependencies,
            $version,
            true
        );
        \wp_enqueue_style(
            $config['handle'],
            $config['asset_url'].$config['style'],
            [],
            $version
        );
    }
}
