<?php

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;
use EnfantTerrible\Models\Abstracts\AbstractMeta;

final class Meta extends AbstractMeta {

    /**
     * Return an array of post meta keys and their respective arguments.
     *
     * Example:
     * [
     *     'example_meta_key' => [
     *         'type' => 'string',
     *         'single' => true,
     *         'show_in_rest' => true,
     *     ],
     * ]
     *
     * @return array
     */
    protected function schema(): array {
        $prefix = 'et-models_' . $this->model_name . '_';
		return [
            $prefix . 'bajada' => [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
            ],
            $prefix . 'excerpt' => [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
            ],
            $prefix . 'authors' => [
                'type' => 'array',
                'single' => true,
                'show_in_rest' => [
                    'schema' => [
                        'type'  => 'array',
                        'items' => [
                            'type'       => 'object',
                            'properties' => [
                                'id'   => [ 'type' => 'string' ],
                                'name' => [ 'type' => 'string' ],
                                'url'  => [ 'type' => 'string' ],
                            ],
                        ],
                    ],
                ],
            ],
            $prefix . 'images' => [
                'type' => 'array',
                'single' => true,
                'show_in_rest' => [
                    'schema' => [
                        'type'  => 'array',
                        'items' => [
                            'type'       => 'object',
                            'properties' => [
                                'id'   => [ 'type' => 'string' ],
                                'url' => [ 'type' => 'string' ],
                                'alt'  => [ 'type' => 'string' ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
