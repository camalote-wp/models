<?php
namespace EnfantTerrible\Models\Abstracts;

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
     * Returns an array of patterns to register for the view.
     *
     * Each pattern should be an array with the following keys:
     * - slug:     The pattern slug, e.g. 'et-models/fotoperiodismo-base-meta'.
     * - path:     The pattern filename, e.g. 'fotoperiodismo-base-meta.php'.
     * - title:    The human-readable title for the pattern.
     * - inserter: Whether the pattern should appear in the inserter. Defaults to false.
     *
     * @since 1.0.0
     * @access public
     * @return array
     */
    public function get_patterns(): array {
        return [];
    }

    /**
     * Returns the absolute path to the asset directory for the given type.
     *
     * @since 1.0.0
     * @access private
     * @param string $type The asset type: 'templates' or 'patterns'.
     * @return string
     */
    private function get_asset_path( string $type ): string {
        $class       = static::class;
        $parts       = explode( '\\', $class );
        $folder_name = $parts[ count( $parts ) - 2 ];
        return ET_MODELS_DIR . 'app/Definitions/' . $folder_name . '/' . $type;
    }

    /**
     * Returns the content of an asset file.
     *
     * @since 1.0.0
     * @access private
     * @param string $type The asset type: 'templates' or 'patterns'.
     * @param string $path The asset filename.
     * @return string
     */
    private function get_asset_content( string $type, string $path ): string {
        ob_start();
        include $this->get_asset_path( $type ) . '/' . $path;
        return ob_get_clean();
    }

    /**
     * Registers all assets of the given type.
     *
     * Iterates over the assets returned by get_templates() or get_patterns()
     * and registers them with WordPress using the appropriate registration function.
     *
     * @since 1.0.0
     * @access private
     * @param string $type The asset type: 'templates' or 'patterns'.
     * @return void
     */
    private function register_assets( string $type ): void {
        $assets = $type === 'templates' ? $this->get_templates() : $this->get_patterns();

        if ( empty( $assets ) ) {
            return;
        }

        foreach ( $assets as $asset ) {
            $content = $this->get_asset_content( $type, $asset['path'] );

            if ( $type === 'templates' ) {
                register_block_template( "et-models//{$asset['slug']}", [
                    'title'   => $asset['title'],
                    'content' => $content,
                ] );
            } else {
                // var_dump( $asset );
                register_block_pattern( $asset['slug'], [
                    'title'    => $asset['title'],
                    'content'  => $content,
                    'inserter' => $asset['inserter'] ?? false,
                ] );
            }
        }
    }

    /**
     * Registers the block templates defined in get_templates().
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function register_templates(): void {
        $this->register_assets( 'templates' );
    }

    /**
     * Registers the block patterns defined in get_patterns().
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function register_patterns(): void {
        $this->register_assets( 'patterns' );
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
        $this->register_patterns();
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