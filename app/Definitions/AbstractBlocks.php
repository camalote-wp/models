<?php

namespace EnfantTerrible\Models\Definitions;

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
        // 1. Get the full class name of the Child (e.g., ...\Fotoperiodismo\Blocks)
        $class = static::class;

        // 2. Break it into parts
        $parts = explode( '\\', $class );

        // 3. The folder name is the second to last part (Fotoperiodismo)
        // [ ... 'Definitions', 'Fotoperiodismo', 'Blocks' ]
        $folder_name = $parts[ count( $parts ) - 2 ];

        // 4. Build path relative to THIS file (AbstractBlocks.php is in Definitions/)
        return __DIR__ . '/' . $folder_name . '/blocks/build';
    }
}