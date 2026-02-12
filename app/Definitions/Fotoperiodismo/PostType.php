<?php

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

final class PostType {
	
    public static function register( string $model_name ): void {
        register_post_type( $model_name, self::args() );
    }

    private static function args(): array {
        $labels = array(
			'name'                     => __( 'Fotogalerías', 'et-models' ),
			'singular_name'            => __( 'Fotogalería', 'et-models' ),
			'add_new'                  => __( 'Agregar nueva', 'et-models' ),
			'add_new_item'             => __( 'Agregar nueva Fotogalería', 'et-models' ),
			'edit_item'                => __( 'Editar Fotogalería', 'et-models' ),
			'new_item'                 => __( 'Nueva Fotogalería', 'et-models' ),
			'view_item'                => __( 'Ver Fotogalería', 'et-models' ),
			'view_items'               => __( 'Ver Fotogalerías', 'et-models' ),
			'search_items'             => __( 'Buscar Fotogalerías', 'et-models' ),
			'not_found'                => __( 'No se encontraron Fotogalerías.', 'et-models' ),
			'not_found_in_trash'       => __( 'No se encontraron Fotogalerías en la papelera.', 'et-models' ), 'parent_item_colon'        => __( 'Parent Fotogalerías.', 'et-models' ),
			'parent_item_colon'        => __( 'Fotogalería Padre:', 'et-models' ),
			'all_items'                => __( 'Todas las Fotogalerías', 'et-models' ),
			'archives'                 => __( 'Archivos de Fotogalerías', 'et-models' ),
			'attributes'               => __( 'Atributos de Fotogalería', 'et-models' ),
			'insert_into_item'         => __( 'Insertar en Fotogalería', 'et-models' ),
			'uploaded_to_this_item'    => __( 'Subido a esta Fotogalería', 'et-models' ),
			'featured_image'           => __( 'Imagen destacada', 'et-models' ),
			'set_featured_image'       => __( 'Establecer imagen destacada', 'et-models' ),
			'remove_featured_image'    => __( 'Eliminar imagen destacada', 'et-models' ),
			'use_featured_image'       => __( 'Usar como imagen destacada', 'et-models' ),
			'menu_name'                => __( 'Fotogalerías', 'et-models' ),
			'filter_items_list'        => __( 'Filtrar lista de Fotogalerías', 'et-models' ),
			'filter_by_date'           => __( 'Filtrar por fecha', 'et-models' ),
			'items_list_navigation'    => __( 'Navegación de lista de Fotogalerías', 'et-models' ),
			'items_list'               => __( 'Lista de Fotogalerías', 'et-models' ),
			'item_published'           => __( 'Fotogalería publicada.', 'et-models' ),
			'item_published_privately' => __( 'Fotogalería publicada en privado.', 'et-models' ),
			'item_reverted_to_draft'   => __( 'Fotogalería revertida a borrador.', 'et-models' ),
			'item_scheduled'           => __( 'Fotogalería programada.', 'et-models' ),
			'item_updated'             => __( 'Fotogalería actualizada.', 'et-models' ),
			'item_link'                => __( 'Enlace a la Fotogalería', 'et-models' ),
			'item_link_description'    => __( 'Enlace a la Fotogalería.', 'et-models' ),

		);

		$args = array(

			'labels'                => $labels,
			'description'           => __( 'Fotogalerías', 'et-models' ),
			'public'                => false,
			'hierarchical'          => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => false,
			'show_in_admin_bar'     => false,
			'show_in_rest'          => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-megaphone',
			'capability_type'       => 'post',
			'capabilities'          => array(),
			'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'taxonomies'            => array(),
			'has_archive'           => false,
			'rewrite'               => array(
				'slug'       => 'example-models',
			),
			'query_var'             => true,
			'can_export'            => true,
			'delete_with_user'      => false,
			'template'              => array(),
			'template_lock'         => false,

		);

        return $args;
    }

}
