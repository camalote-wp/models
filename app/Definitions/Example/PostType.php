<?php

namespace CamaloteWP\Models\Definitions\Example;

use CamaloteWP\Models\Abstracts\AbstractPostType;

final class PostType extends AbstractPostType {
	/**
	 * Returns an array of arguments to register a post type.
	 *
	 * @return array {
	 *     @type string $key The key of the argument.
	 *     @type mixed $value The value of the argument.
	 * }
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 */
    protected function args(): array {
		return [
			'labels' => [
				'name'          => __( 'Examples', 'text-domain' ),
				'singular_name' => __( 'Example', 'text-domain' ),
			],
			'public'       => true,
			'show_in_rest' => true,
			'supports'     => [ 'title', 'editor' ],
		];
    }

}
