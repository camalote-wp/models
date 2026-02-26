<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<?php
	$post_id = get_the_ID();
	$excerpt_long = get_post_meta($post_id, 'et-models_fotoperiodismo_excerpt_long', true);
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo wpautop( esc_html( $excerpt_long )); ?>
</div>
