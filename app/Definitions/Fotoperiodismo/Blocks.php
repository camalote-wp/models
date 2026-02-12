<?php

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

use EnfantTerrible\Models\Definitions\AbstractBlocks;

final class Blocks extends AbstractBlocks {

    /**
     * Get the hooks for the class.
     *
     * This function returns an array of hooks to be registered with WordPress.
     * The array should contain the following structure:
     *
     * [
     *     [
     *         'type' => 'action' | 'filter',
     *         'hook' => string,
     *         'callback' => string,
     *         'priority' => int,
     *         'accepted_args' => int
     *     ]
     * ]
     *
     * @return array
     */
    public function get_hooks(): array {
        return [
            [
                'type' => 'filter',
                'hook' => 'allowed_block_types_all',
                'callback' => 'restrict',
                'priority' => 10,
                'accepted_args' => 2
            ]
        ];
    }

	/**
	 * Restricts the allowed block types based on the current post type.
	 *
	 * If the current post type is 'fotoperiodismo', only our custom blocks are allowed.
	 * If the current post type is not 'fotoperiodismo', all our custom blocks are hidden.
	 *
	 * @param array $allowed The allowed block types.
	 * @param object $context The current context.
	 *
	 * @return array|bool The restricted block types.
	 */
    public function restrict( $allowed, $context ): array|bool {
		if ( $context->post?->post_type === $this->model_name ) {
            return [
                'enfantterrible/fotoperiodismo-bajada'
            ];
        }
        
        // If $allowed is 'true', it means "all blocks". We need to convert it to 
        // an actual array of block names so we can remove ours.
        if ( $allowed === true ) {
            $allowed = array_keys( \WP_Block_Type_Registry::get_instance()->get_all_registered() );
        }

        // Now filter out our custom blocks from the list
        if ( is_array( $allowed ) ) {
            $allowed = array_filter( $allowed, function( $block_name ) {
                // Remove block if it starts with your namespace
                return strpos( $block_name, 'enfantterrible/fotoperiodismo-' ) === false;
            });

            // Reset keys so it sends a JSON Array, not a JSON Object
            return array_values( $allowed );
        }
        
        return $allowed;
    }
}