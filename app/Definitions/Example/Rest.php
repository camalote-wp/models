<?php

namespace CamaloteWP\Models\Definitions\Example;

use CamaloteWP\Models\Abstracts\AbstractRest;

final class Rest extends AbstractRest {

	public function get_hooks(): array {
		return [
			[
				'type'          => 'filter',
				'hook'          => 'rest_prepare_example',
				'callback'      => 'extend_rest_response',
				'priority'      => 10,
				'accepted_args' => 3,
			],
		];
	}

	public function extend_rest_response( $response, $post, $request ) {
		$data = $response->data['meta'] ?? [];

		$data['_custom_field'] = get_post_meta(
			$post->ID,
			'_custom_field',
			true
		);

		$response->data['meta'] = $data;

		return $response;
	}
}
