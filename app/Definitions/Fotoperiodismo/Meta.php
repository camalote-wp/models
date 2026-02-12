<?php

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

final class Meta {

    public static function register( string $model_name ): void {
        foreach ( self::schema() as $key => $args ) {
            register_post_meta( $model_name, $key, $args );
        }
    }

    private static function schema(): array {
        return [
            'example_meta_key' => [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
            ],
        ];
    }
}
