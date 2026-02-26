<?php
/**
 * tenup markup
 *
 * @package tenup\Blocks\tenup
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 */

?>
<?php
$post_id = get_the_ID();
$authors = get_post_meta($post_id, 'et-models_fotoperiodismo_authors', true);
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <?php if ( !empty($authors) ) : ?>
        <?php foreach ( $authors as $index => $author ) : ?>
            <a href="<?php echo esc_url($author['url']); ?>">
				<?php echo esc_html($author['name']); ?>
			</a>
			<?php echo $index < count($authors) - 1 ? '<span>, <span>' : ''; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>