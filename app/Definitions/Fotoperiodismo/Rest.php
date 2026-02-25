<?php

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

use EnfantTerrible\Models\Abstracts\AbstractRest;

final class Rest extends AbstractRest {

    /**
	 * Register any hooks and filters.
	 *
	 * @return array
	 */
	public function get_hooks(): array {
        return [
            [
                'type' => 'filter',
                'hook' => 'rest_prepare_fotoperiodismo',
                'callback' => 'expose_carbon_fields',
                'priority' => 10,
                'accepted_args' => 3
            ]
        ];
	}

	/**
	 * Expose Carbon Fields data via the REST API.
	 *
	 * This filters the `rest_prepare_fotoperiodismo` response to include Carbon Fields data.
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param WP_Post          $post     The post object.
	 * @param WP_REST_Request  $request  The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function expose_carbon_fields( $response, $post, $request ) {
		$field_map = [
			'_crb_enfantterrible_fotoperiodismo_gallery'   => [
				'type'   => 'repeater',
				'subkey' => 'value',
			],
			'_crb_enfantterrible_fotoperiodismo_authors'   => [
				'type'      => 'complex_repeater',
				'subfields' => [ 'nombre', 'link' ],
			],
			'_crb_enfantterrible_fotoperiodismo_desc_short' => [ 'type' => 'single' ],
			'_crb_enfantterrible_fotoperiodismo_desc_long' => [ 'type' => 'single' ],
		];

		$meta = get_post_meta( $post->ID );
		$data = $response->data['meta'] ?? [];

		foreach ( $field_map as $base_key => $config ) {
			switch ( $config['type'] ) {
				case 'single':
					$data[ $base_key ] = maybe_unserialize( $meta[ $base_key ][0] ?? '' );
					break;

				case 'repeater':
					// Flat array: _crb_gallery|||0|value
					$items = [];
					foreach ( $meta as $key => $value ) {
						if ( preg_match( '/^' . preg_quote( $base_key, '/' ) . '\|\|\|(\d+)\|' . preg_quote( $config['subkey'], '/' ) . '$/', $key, $m ) ) {
							$items[ (int) $m[1] ] = $value[0] ?? null;
						}
					}
					ksort( $items );
					$data[ $base_key ] = array_values( $items );
					break;

				case 'complex_repeater':
					$items = [];
					foreach ( $config['subfields'] as $subfield ) {
						foreach ( $meta as $key => $value ) {
							if ( preg_match(
								'/^' . preg_quote( $base_key, '/' ) . '\|' . preg_quote( $subfield, '/' ) . '\|(\d+)\|\d+\|value$/',
								$key,
								$m
							) ) {
								$items[ (int) $m[1] ][ $subfield ] = $value[0] ?? null;
							}
						}
					}
					ksort( $items );
					$data[ $base_key ] = array_values( $items );
					break;
			}
		}

		$response->data['meta'] = $data;

		return $response;
	}
}
