<?php
namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

use EnfantTerrible\Models\Abstracts\AbstractView;

final class View extends AbstractView {

    /**
     * Returns the templates to register for the Fotoperiodismo model.
     *
     * @since 1.0.0
     * @access public
     * @return array
     */
    public function get_templates(): array {
        return [
            [
                'slug'  => 'archive-fotoperiodismo',
                'path'  => 'archive-fotoperiodismo.html',
                'title' => __( 'Archivo de Fotoperiodismo', 'et-models' ),
            ],
            [
                'slug'  => 'single-fotoperiodismo',
                'path'  => 'single-fotoperiodismo.html',
                'title' => __( 'Entrada de Fotoperiodismo', 'et-models' ),
            ],
        ];
    }

    /**
     * Returns the patterns to register for the Fotoperiodismo model.
     *
     * @since 1.0.0
     * @access public
     * @return array
     */
    public function get_patterns(): array {
        return [
            [
                'slug'  => 'et-models/single-fotoperiodismo-base-meta',
                'path'  => 'single-fotoperiodismo-base-meta.php',
                'title' => __( 'Fotoperiodismo: Meta', 'et-models' ),
            ],
            [
                'slug'  => 'et-models/fotoperiodismo-feed',
                'path'  => 'fotoperiodismo-feed.php',
                'title' => __( 'Fotoperiodismo: Feed', 'et-models' ),
            ],
        ];
    }

    /**
     * Returns the hooks to register for the Fotoperiodismo view.
     *
     * @since 1.0.0
     * @access public
     * @return array
     */
    public function get_hooks(): array {
        return [
            [
                'type'          => 'action',
                'hook'          => 'wp_enqueue_scripts',
                'callback'      => 'enqueue_assets',
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }

    /**
     * Enqueues the assets for the Fotoperiodismo templates.
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function enqueue_assets(): void {
        $prefix = 'et-models-' . $this->model_name;

        if ( is_post_type_archive( $this->model_name ) ) {
            wp_enqueue_style(
                $prefix . '-archive',
                ET_MODELS_URL . 'assets/build/fotoperiodismo/css/templates/archive-fotoperiodismo.css',
                [],
                '1.0.0'
            );
        }

        if ( is_singular( $this->model_name ) ) {
            wp_enqueue_style(
                $prefix . '-single',
                ET_MODELS_URL . 'assets/build/fotoperiodismo/css/templates/single-fotoperiodismo.css',
                [],
                '1.0.0'
            );
        }

        wp_enqueue_style(
            $prefix . '-patterns',
            ET_MODELS_URL . 'assets/build/fotoperiodismo/css/patterns/index.css',
            [],
            '1.0.0'
        );
    }
}