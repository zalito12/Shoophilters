<?php

namespace GonGarceIO\WooFilters\Includes;

/**
 * Class to load all block logic, actions and filters.
 */
class Woofilters_Block_Loader {

	/**
	 * Set ups the blocks, actions and filters.
	 */
	public function load() {
		add_action( 'woocommerce_register_taxonomy', array( $this, 'woofilters_show_in_rest' ) );
		add_filter( 'block_categories_all', array( $this, 'add_category_filter' ) );

		$this->register_blocks();
	}

	/**
	 * Show WC taxonomies in admin rest to load the list on product attribute filter block.
	 */
	public function woofilters_show_in_rest() {
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( $attribute_taxonomies ) {
			foreach ( $attribute_taxonomies as $tax ) {
				$name = wc_attribute_taxonomy_name( $tax->attribute_name );

				add_filter(
					"woocommerce_taxonomy_args_{$name}",
					function( $data ) {
						$data['show_in_rest'] = true;
						return $data;
					}
				);
			}
		}
	}

	/**
	 * Filter to add a new category to block categories list.
	 *
	 * @param array $categories The categories.
	 * @return array The categories with new ones.
	 */
	public function add_category_filter( $categories ) {
		$categories[] = array(
			'slug'  => 'woofilters',
			'title' => 'WooFilters',
		);
		return $categories;
	}

	/**
	 * Register public blocks.
	 */
	public function register_blocks() {
		register_block_type( WOOFILTERS_PATH . '/blocks/build/product-categories' );
		register_block_type( WOOFILTERS_PATH . '/blocks/build/product-attributes' );
		register_block_type( WOOFILTERS_PATH . '/blocks/build/responsive-container' );
		register_block_type( WOOFILTERS_PATH . '/blocks/build/apply-button' );
		register_block_type( WOOFILTERS_PATH . '/blocks/build/remove-button' );
		register_block_type( WOOFILTERS_PATH . '/blocks/build/product-orderby' );
		register_block_type( WOOFILTERS_PATH . '/blocks/build/product-price' );
	}
}
