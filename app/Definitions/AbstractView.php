<?php

namespace EnfantTerrible\Models\Definitions;

use EnfantTerrible\Models\Interfaces\Registerable;
use EnfantTerrible\Models\Interfaces\Hookable;

abstract class AbstractView implements Registerable, Hookable {

    protected string $model_name;

    /**
     * Initializes the AbstractView class and sets its $model_name property.
     *
     * @param string $model_name The name of the model to register the view for.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct( string $model_name ) {
        $this->model_name = $model_name;
    }

    /**
     * Returns an array of templates to register for the view.
     *
     * Each template should be an array with the following keys:
     * - slug:  The template slug, e.g. 'archive-fotoperiodismo'.
     * - path:  The template filename, e.g. 'archive-fotoperiodismo.html'.
     * - title: The human-readable title for the template.
     *
     * @since 1.0.0
     * @access public
     * @return array
     */
    public function get_templates(): array {
        return [];
    }

    /**
     * Returns the absolute path to the templates directory for the child class.
     *
     * @since 1.0.0
     * @access private
     * @return string
     */
    private function get_templates_path(): string {
        $class       = static::class;
        $parts       = explode( '\\', $class );
        $folder_name = $parts[ count( $parts ) - 2 ]; // e.g. 'Fotoperiodismo'
        return __DIR__ . '/' . $folder_name . '/templates';
    }

    /**
     * Returns the content of a template file.
     *
     * @since 1.0.0
     * @access public
     * @param string $path The template filename.
     * @return string
     */
    public function get_template_content( string $path ): string {
        ob_start();
        include $this->get_templates_path() . '/' . $path;
        return ob_get_clean();
    }

    /**
     * Registers the block templates defined in get_templates().
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function register_templates(): void {
        $templates = $this->get_templates();

        if ( empty( $templates ) ) {
            error_log( "No templates found for model {$this->model_name}" );
            return;
        }

        foreach ( $templates as $template ) {
            register_block_template( "et-models//{$template['slug']}", [
                'title'   => $template['title'],
                'content' => $this->get_template_content( $template['path'] ),
            ] );
        }
    }

    /**
     * Registers the view. Called on init by Plugin.php.
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function register(): void {
        $this->register_templates();
    }

    /**
     * Returns the hooks to register for the view.
     *
     * @since 1.0.0
     * @access public
     * @return array
     */
    public function get_hooks(): array {
        return [];
    }
}