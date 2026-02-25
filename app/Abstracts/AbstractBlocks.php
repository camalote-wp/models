<?php

namespace EnfantTerrible\Models\Abstracts;

use EnfantTerrible\Models\Interfaces\Registerable;
use EnfantTerrible\Models\Interfaces\Hookable;

abstract class AbstractBlocks implements Registerable, Hookable {
    protected string $model_name;

    public function __construct( string $model_name ) {
        $this->model_name = $model_name;
    }

	public function register(): void {
        $path = $this->get_automatic_path();
        
        // Safety check in case the folder doesn't exist
        if ( is_dir( $path ) ) {
            foreach ( glob( $path . '/*/block.json' ) as $file ) {
                register_block_type( dirname( $file ) );
            }
        }
    }

    public function get_hooks(): array {
        return [];
    }

    private function get_automatic_path(): string {
        $dir_path = ET_MODELS_DIR . 'assets/build/' . $this->model_name . '/blocks';
        return $dir_path;
    }
}