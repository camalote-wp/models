<?php

namespace CamaloteWP\Models\Abstracts;

use CamaloteWP\Models\Interfaces\Registerable;
use CamaloteWP\Models\Interfaces\Hookable;

abstract class AbstractBlocks implements Registerable, Hookable {
    protected string $model_name;

    abstract protected function get_block_paths(): array;

	public function register(): void {
        foreach ( $this->get_block_paths() as $path ) {
            if ( is_dir( $path ) ) {
                foreach ( glob( $path . '/*/block.json' ) as $file ) {
                    register_block_type( dirname( $file ) );
                }
            }
        }
    }

    public function get_hooks(): array {
        return [];
    }
}