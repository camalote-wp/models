<?php
namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

use EnfantTerrible\Models\Definitions\AbstractModelAdminPage;

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
            'parent_slug' => 'edit.php?post_type=fotoperiodismo',
            'page_title'  => 'Migración',
            'menu_slug'   => 'fotoperiodismo-migration',
            'screen_id'   => 'fotoperiodismo_page_fotoperiodismo-migration',
        ];
    }

    public function get_asset_config(): array {
        return [
            'handle'    => 'et-models-fotoperiodismo-admin',
            'asset_dir' => ET_MODELS_DIR . 'assets/build/fotoperiodismo/js/admin-page/',
            'asset_url' => ET_MODELS_URL . 'assets/build/fotoperiodismo/js/admin-page/',
            'script'    => 'index.js',
            'style'     => 'index.css',
            'deps_file' => 'index.asset.php',
        ];
    }

    public function render_page(): void {
        echo '<div id="fotoperiodismo-migration-page"></div>';
    }
}