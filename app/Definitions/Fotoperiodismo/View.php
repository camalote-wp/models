<?php

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

use EnfantTerrible\Models\Definitions\AbstractView;

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
        ];
    }

    public function get_hooks(): array {
        return [
                [
                    'type' => 'action',
                    'hook' => 'wp_enqueue_scripts',
                    'callback' => 'enqueue_assets',
                    'priority' => 10,
                    'accepted_args' => 1,
                ],
        ];
    }

    /**
     * Enqueues the assets for the Fotoperiodismo templates.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue_assets(): void {
        if ( ! is_singular( $this->model_name ) && ! is_post_type_archive( $this->model_name ) ) {
            return;
        }

        $handle = 'et-models-' . $this->model_name . '-templates';

        if ( is_post_type_archive( $this->model_name ) ) {
            wp_enqueue_style(
                $handle,
                plugins_url( 'css/archive-fotoperiodismo.css', __FILE__ ),
                [],
                '1.0.0'
            );
        }
    }
}