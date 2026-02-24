<?php

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;
use EnfantTerrible\Models\Definitions\AbstractAdminPage;

final class AdminPage extends AbstractAdminPage {

    /**
	 * Register any hooks and filters.
	 *
	 * @return array
	 */
	public function get_hooks(): array {
        return [
            [
				'type' => 'action',
				'hook' => 'admin_menu',
				'callback' => 'register_submenu_page',
				'priority' => 10,
            ],
			[
				'type' => 'action',
				'hook' => 'admin_enqueue_scripts',
				'callback' => 'enqueue_assets',
				'priority' => 10
			]
        ];
	}

	public function register_submenu_page(): void {
		add_submenu_page(
			'edit.php?post_type=fotoperiodismo',
			'Migración',
			'Migración',
			'manage_options',
			'fotoperiodismo-migration',
			[ $this, 'render_page' ]
		);
	}

	public function render_page(): void {
		// Render the page content here
		echo '<div id="fotoperiodismo-migration-page"></div>';
	}

	public function enqueue_assets(): void {
		$screen = get_current_screen();
		if ( $screen->id !== 'fotoperiodismo_page_fotoperiodismo-migration' ) {
			return;
		}

		$deps = plugin_dir_path( __FILE__ ) . 'assets/build/admin-page/index.asset.php';
		if ( file_exists( $deps ) ) {
			$asset = require $deps;
			$dependencies = $asset['dependencies'] ?? [];
			$version = $asset['version'] ?? '1.0.0';
		}

		wp_enqueue_script(
			'et-models-fotoperiodismo-admin',
			plugins_url( 'assets/build/admin-page/index.js', __FILE__ ),
			$dependencies,
			$version,
			true
		);

		wp_enqueue_style(
			'et-models-fotoperiodismo-admin',
			plugins_url( 'assets/build/admin-page/index.css', __FILE__ ),
			[],
			'1.0.0'
		);
	}
}
