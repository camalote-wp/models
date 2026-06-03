<?php

namespace CamaloteWP\Models\Definitions\Example;
use CamaloteWP\Models\Abstracts\AbstractMeta;

final class Meta extends AbstractMeta {

	/**
	 * Return an array of post meta keys and their registration arguments.
	 *
	 * @return array
	 */
	protected function schema(): array {
		$prefix = 'plugin_' . $this->model_name . '_';

		return [
			$prefix . 'example_field' => [
				'type'         => 'string',
				'single'       => true,
				'show_in_rest' => true,
			],
		];
	}
}
