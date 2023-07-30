<?php

use GonGarceIO\WooFilters\Includes\Woofilters_Product_Query;

const WOOFILTERS_PA_PREFIX = 'pa_';

$show_empty = $attributes['showEmpty'];
$show_count = $attributes['showTotal'];
$style      = $attributes['style'];
$row_size   = absint( $attributes['rowSize'] );
$filtering  = $attributes['filtering'];

if ( ! isset( $attributes['taxonomy'] ) || empty( $attributes['taxonomy'] ) ) {
	return 'You must select an attribute';
} else {
	$name = $attributes['taxonomy'];

	// Remove 'pa_' preffix if present.
	$pos = strpos( $attributes['taxonomy'], WOOFILTERS_PA_PREFIX );
	if ( false !== $pos ) {
		$name = substr_replace( $name, '', $pos, strlen( WOOFILTERS_PA_PREFIX ) );
	}

	$filter_terms = array(
		'taxonomy'     => $attributes['taxonomy'],
		'hide_empty'   => ! $show_empty,
		'pad_counts'   => false,
		'hierarchical' => true,
	);

	$terms = get_terms( $filter_terms );
	if ( ! is_array( $terms ) || empty( $terms ) ) {
		$terms = array();
	}

	$product_query = new Woofilters_Product_Query();
	$counts        = $product_query->get_attribute_counts( WOOFILTERS_PA_PREFIX . $name );

	$filter_name     = 'filter_' . $name;
	$filter_query    = 'query_type_' . $name;
	$current_filters = explode( ',', get_query_var( $filter_name, '' ) );
	$width           = intval( 100 / $row_size );

	$is_button = 'button' === $filtering['navigation'];
	$group     = $filtering['group'];
	$class     = 'woofilters-filter-navigation-' . $filtering['navigation'] . ' woofilters-filter-group-' . $filtering['group'];

	if ( ! $show_empty && count( $counts ) === 0 ) :

		?>
		<div class="<?php echo esc_attr( $attributes['className'] ); ?>">
			<span class="woofilters-attribute-empty <?php echo esc_attr( $class ); ?>"
				data-group="<?php echo esc_attr( $group ); ?>"
				data-value="<?php echo esc_attr( $term->slug ); ?>"
				data-filter="<?php echo esc_attr( $filter_name ); ?>"
				data-query="<?php echo esc_attr( $filter_query ); ?>">
				<?php esc_html_e( 'No products match with this filters', 'astra-acs' ); ?>
			</span>
		</div>
		<?php
	else :
		?>
	<div class="<?php echo esc_attr( $attributes['className'] ); ?>">
		<ul class="woofilters-attributes-list <?php echo 'grid' === $style ? 'woofilters-grid' : ''; ?>">
				<?php
				foreach ( $terms as $term ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					$selected = in_array( $term->slug, $current_filters );
					$count    = isset( $counts[ $term->term_id ] ) ? $counts[ $term->term_id ] : 0;
					if ( ! $show_empty && 0 === $count ) {
						continue;
					}
					?>
					<li class="woofilters-attribute-item <?php echo $selected ? 'current ' : ''; ?><?php echo esc_attr( $class ); ?>"
						style="width: <?php echo esc_attr( $width ); ?>%"
						data-group="<?php echo esc_attr( $group ); ?>"
						data-value="<?php echo esc_attr( $term->slug ); ?>"
						data-filter="<?php echo esc_attr( $filter_name ); ?>"
						data-query="<?php echo esc_attr( $filter_query ); ?>">
						<div class="wc-block-components-checkbox wc-block-checkbox-list__checkbox">
							<label for="<?php echo esc_attr( $name . '_' . $term->name ); ?>">
								<input id="<?php echo esc_attr( $name . '_' . $term->name ); ?>"
									class="wc-block-components-checkbox__input" type="checkbox" aria-invalid="false" <?php echo $selected ? 'checked' : ''; ?>>
								<svg class="wc-block-components-checkbox__mark" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 20"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"></path></svg>
								<span class="wc-block-components-checkbox__label"><?php echo esc_html( $term->name ); ?></span>
							</label>
							<?php
							if ( 'always' === $show_count ) :
								?>
							<span class="woofilters-attribute-count">
									<?php echo esc_html( $count ); ?>
							</span>
								<?php
							elseif ( 'current' === $show_count ) :
								?>
								<span class="woofilters-attribute-count woofilters-attribute-count-current">
									<?php echo esc_html( $count ); ?>
								</span>
								<?php
							endif;
							?>
						</div>
					</li>
			<?php endforeach; ?>
		</ul>
	</div>
			<?php
	endif;
}
