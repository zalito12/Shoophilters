<?php
/**
 * Render order by field.
 */

if ( ! function_exists( 'woofilters_get_order_filter' ) ) {
	/**
	 * Update this function with wc-template-functions.php:woocommerce_catalog_ordering()
	 *
	 * @param string  $filtering The type of filtering (ajax, button, standard).
	 * @param boolean $show_always If show  the field even when no results or there is just one page.
	 * @param string  $type The type of control (select, list).
	 * @param boolean $is_button If this filter works with apply filters button.
	 * @param string  $group The name of the filters group.
	 * @param string  $class Classes to apply.
	 */
	function woofilters_get_order_filter( $filtering, $show_always, $type, $is_button, $group, $class ) {

		if ( ! $show_always && ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) ) {
			return;
		}

		$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
		$catalog_orderby_options = apply_filters(
			'woocommerce_catalog_orderby',
			array(
				'menu_order' => __( 'Default sorting', 'woocommerce' ),
				'popularity' => __( 'Sort by popularity', 'woocommerce' ),
				'rating'     => __( 'Sort by average rating', 'woocommerce' ),
				'date'       => __( 'Sort by latest', 'woocommerce' ),
				'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
				'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
			)
		);

		$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
		// phpcs:disable WordPress.Security
		$orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby;
		// phpcs:enable WordPress.Security

		if ( wc_get_loop_prop( 'is_search' ) ) {
			$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );

			unset( $catalog_orderby_options['menu_order'] );
		}

		if ( ! $show_default_orderby ) {
			unset( $catalog_orderby_options['menu_order'] );
		}

		if ( ! wc_review_ratings_enabled() ) {
			unset( $catalog_orderby_options['rating'] );
		}

		if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
			$orderby = current( array_keys( $catalog_orderby_options ) );
		}

		if ( 'select' === $type ) {
			include WOOFILTERS_PATH . 'public/templates/filter-orderby-select.php';
		} else {
			include WOOFILTERS_PATH . 'public/templates/filter-orderby-list.php';
		}
	}
}


if ( ! function_exists( 'woocommerce_catalog_ordering' ) ) {

	echo '<div>No woocommerce found</div>';

} else {

	$filtering = $attributes['filtering'];
	$is_button = 'button' === $filtering['navigation'];
	$group     = 	$filtering['group'];
	$class     = 'woofilters-filter-navigation-' . $filtering['navigation'] . ' woofilters-filter-group-' . $filtering['group'];

	woofilters_get_order_filter( $filtering , $attributes['showAlways'], $attributes['type'], $is_button, $group, $class );
}
