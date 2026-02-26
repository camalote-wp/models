<?php
/**
 * Title: Fotoperiodismo feed
 * Slug: et-models/fotoperiodismo-feed
 * Inserter: no
 *
 * @package et-models
 * @since 1.0.0
 */
?>
<!-- wp:group {
	"className": "et-models-fotoperiodismo-feed",
	"align":"full",
 	"layout": {
		"type":"flex"
	},
} -->
<div class="wp-block-group alignfull et-models-fotoperiodismo-feed">
	<!-- wp:query {
		"namespace": "et-models/fotoperiodismo-feed",
		"className": "et-models-fotoperiodismo-feed-query",
		"query":{
			"inherit":true
		},
		"layout": {
			"type":"flex"
		},
	} -->
	<div class="wp-block-query et-models-fotoperiodismo-feed-query">
		<!-- wp:post-template {"className":"et-models-fotoperiodismo-feed-items","layout":{"type":"flex"}} -->
			<!-- wp:group {
				"layout": {
					"type":"flex",
					"orientation":"vertical"
				}
			}
			-->
			<div class="wp-block-group et-models-fotoperiodismo-feed-item">
				<!-- wp:post-featured-image /-->
				<!-- wp:group {
					"style": {
						"spacing": {
							"blockGap":"0"
						}
					}
				}
				-->
				<div class="wp-block-group">
					<!-- wp:post-title {"level":2,"isLink":true} /-->
					<!-- wp:post-excerpt /-->
					<!-- wp:pattern {"slug":"et-models/single-fotoperiodismo-base-meta"} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
		<!-- wp:query-no-results -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'No results', 'et-theme' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
		<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex", "justifyContent":"space-between"}} -->
			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group et-models-fotoperiodismo-feed-pagination-previous">
				<!-- wp:query-pagination-previous {"label":"<?php esc_html_e( 'Anterior', 'et-theme' ); ?>"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group et-models-fotoperiodismo-feed-pagination-next">
				<!-- wp:query-pagination-next {"label":"<?php esc_html_e( 'Siguiente', 'et-theme' ); ?>"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"layout":{"type":"default"}} -->
		   <div class="wp-block-group et-models-fotoperiodismo-feed-pagination-numbers">
			   <!-- wp:query-pagination-numbers /-->
		   </div>
		   <!-- /wp:group -->
		<!-- /wp:query-pagination -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->