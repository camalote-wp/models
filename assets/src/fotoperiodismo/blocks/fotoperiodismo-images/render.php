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

$post_id = get_the_ID();
$images  = get_post_meta( $post_id, 'et-models_fotoperiodismo_images', true );
?>

<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
    <?php foreach ( $images as $img ) : ?>

        <?php
        $id = isset( $img['id'] ) ? (int) $img['id'] : 0;

        if ( ! $id ) {
            continue;
        }

        // Thumbnail shown in the grid.
        $thumb = wp_get_attachment_image_url( $id, 'large' );
        // Full size opened in the lightbox.
        $full  = wp_get_attachment_image_url( $id, 'full' );
        $alt   = get_post_meta( $id, '_wp_attachment_image_alt', true );
        $caption = wp_get_attachment_caption( $id );

        if ( ! $thumb ) {
            continue;
        }
        ?>

        <figure class="wp-block-image size-large">
            <a 
                class="glightbox"
                href="<?php echo esc_url( $full ); ?>"
                data-gallery="fotoperiodismo-<?php echo esc_attr( $post_id ); ?>"
                <?php if ( $caption ) : ?>
                    data-description="<?php echo esc_attr( $caption ); ?>"
                <?php endif; ?>
            >
                <img
                    src="<?php echo esc_url( $thumb ); ?>"
                    alt="<?php echo esc_attr( $alt ); ?>"
                    class="wp-image-<?php echo $id; ?>"
                />
            </a>
        </figure>

    <?php endforeach; ?>

</div>