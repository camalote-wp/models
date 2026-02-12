<?php

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;
use EnfantTerrible\Models\Interfaces\Registerable;
use EnfantTerrible\Models\Definitions\AbstractMeta;

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
		return [
            'example_meta_key' => [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
            ],
        ];
    }
}
