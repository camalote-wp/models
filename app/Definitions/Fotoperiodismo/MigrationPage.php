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
            ],
            [
                'type'     => 'action',
                'hook'     => 'admin_enqueue_scripts',
                'callback' => 'enqueue_assets',
                'priority' => 10,
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
            'asset_dir' => plugin_dir_path( __FILE__ ) . 'assets/build/admin-page/',
            'asset_url' => plugins_url( 'assets/build/admin-page/', __FILE__ ),
            'script'    => 'index.js',
            'style'     => 'index.css',
            'deps_file' => 'index.asset.php',
        ];
    }

    public function render_page(): void {
        echo '<div id="fotoperiodismo-migration-page"></div>';
    }
}