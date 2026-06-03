<?php
namespace CamaloteWP\Models\Definitions\Example;

use CamaloteWP\Models\Abstracts\AbstractModelAdminPage;

final class MigrationPage extends AbstractModelAdminPage {
    public function get_hooks(): array {
        return [
            [
                'type'     => 'action',
                'hook'     => 'admin_menu',
                'callback' => 'register_submenu_page',
                'priority' => 10,
				'accepted_args' => 1
            ],
            [
                'type'     => 'action',
                'hook'     => 'admin_enqueue_scripts',
                'callback' => 'enqueue_assets',
                'priority' => 10,
				'accepted_args' => 1
            ]
        ];
    }

    public function get_page_config(): array {
        return [
            'parent_slug' => 'edit.php?post_type=example',
            'page_title'  => 'Migración',
            'menu_slug'   => 'example-page',
            'screen_id'   => 'example_page',
        ];
    }

    public function get_asset_config(): array {
        return [
            'handle'    => 'camalote-wp-models-example-admin',
            'asset_dir' => CAMALOTE_WP_MODELS_DIR . 'assets/build/example/js/admin-page/',
            'asset_url' => CAMALOTE_WP_MODELS_URL . 'assets/build/example/js/admin-page/',
            'script'    => 'index.js',
            'style'     => 'index.css',
            'deps_file' => 'index.asset.php',
        ];
    }

    public function render_page(): void {
        echo '<div id="example-page"></div>';
    }
}