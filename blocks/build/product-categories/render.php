<?php

$show_empty    = isset( $attributes['showEmpty'] ) ? $attributes['showEmpty'] : false;
$show_children = isset( $attributes['showChildren'] ) ? $attributes['showChildren'] : false;
$show_count    = isset( $attributes['showTotal'] ) ? $attributes['showTotal'] : 'never';
$filtering     = $attributes['filtering'];

$filter_terms = array(
	'taxonomy'     => 'product_cat',
	'hide_empty'   => ! $show_empty,
	'pad_counts'   => true,
	'hierarchical' => true,
);

if ( ! $show_children ) {
	$filter_terms['parent'] = 0;
}

$categories = get_terms( $filter_terms );
if ( ! is_array( $categories ) || empty( $categories ) ) {
	$categories = array();
}

// This ensures that no categories with a product count of 0 is rendered.
if ( ! $show_empty ) {
	$categories = array_filter(
		$categories,
		function( $category ) {
			return 0 !== $category->count;
		}
	);
}

$current_cat = get_query_var( 'product_cat', '' );

$is_button = 'button' === $filtering['navigation'];
$group     = $filtering['group'];
$class     = 'woofilters-filter-navigation-' . $filtering['navigation'] . ' woofilters-filter-group-' . $filtering['group'];
?>
<div class="<?php echo esc_attr( $attributes['className'] ); ?>">
	<ul class="woofilters-category-list">
		<?php foreach ( $categories as $category ) : ?>
		<li class="woofilters-category-item <?php echo $category->slug === $current_cat ? 'current ' : ''; ?><?php echo esc_attr( $class ); ?>"
			data-group="<?php echo esc_attr( $group ); ?>"
			data-value="<?php echo esc_attr( $category->slug ); ?>"
			data-filter="product_cat">
			<div class="woofilters-item-radio-label">
				<?php if ( $is_button ) : ?>
					<input class="woofilters-radio-filter" type="radio"
						name="woofilters-radio-category-<?php echo esc_attr( $filtering['group'] ); ?>"
						value="<?php echo esc_attr( $category->slug ); ?>"
						<?php echo $category->slug === $current_cat ? 'checked' : ''; ?> />
					<span class="woofilters-radio-filter-mark"></span>
				<?php endif; ?>
				<a href="<?php echo esc_attr( get_term_link( $category->term_id, 'product_cat' ) ); ?>">
					<span class="woofilters-category-name"><?php echo esc_html( $category->name ); ?></span>
				</a>
				<?php
				if ( 'always' === $show_count ) :
					?>
					<span class="woofilters-category-count">
						<?php echo esc_html( $category->count ); ?>
					</span>
					<?php
				elseif ( 'current' === $show_count ) :
					?>
					<span class="woofilters-category-count woofilters-category-count-current">
						<?php echo esc_html( $category->count ); ?>
					</span>
					<?php
				endif;
				?>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
