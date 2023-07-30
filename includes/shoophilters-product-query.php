<?php

namespace GonGarceIO\Shoophilters\Includes;

use Automattic\WooCommerce\StoreApi\Utilities\ProductQueryFilters;

/**
 * Extends WooCommerce product query filters to prepare request from shoophilters query params.
 *
 * @uses Automattic\WooCommerce\StoreApi\Utilities\ProductQueryFilters
 */
class Shoophilters_Product_Query {

	/**
	 * The currently queried object.
	 *
	 * @var \WP_Term|\WP_Post_Type|\WP_Post|\WP_User|null The queried object.
	 */
	private $queried_object;

	/**
	 * The current request.
	 *
	 * @var \WP_REST_Request The request.
	 */
	private $request;

	/**
	 * The product query filters reference.
	 *
	 * @var ProductQueryFilters The reference.
	 */
	private $product_query_filters;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->product_query_filters = new ProductQueryFilters();
	}

	/**
	 * Get the categories ids from its slugs.
	 *
	 * @param array<string> $categories The categories slugs.
	 * @return array<int> The categories ids.
	 */
	private static function get_categories_ids( $categories = array() ): array {
		$cats = array();
		foreach ( $categories as $i => $slug ) {
			$category = get_term_by( 'slug', $slug, 'product_cat' );
			if ( $category ) {
				$cats[] = $category->term_id;
			}
		}

		return $cats;
	}

	/**
	 * Creates a request from the route and query params.
	 *
	 * @return \WP_REST_Request The request with the filters extracted from query params and route.
	 */
	public static function get_request_from_query(): \WP_REST_Request {
		$request = new \WP_REST_Request();

		$category = get_query_var( 'product_cat', array() );
		if ( ! empty( $category ) ) {
			$cats = self::get_categories_ids( is_array( $category ) ? $category : explode( ',', $category ) );

			if ( ! empty( $cats ) ) {
				$request->set_param( 'category', $cats );
				$request->set_param( 'category_operator', 'in' );
			}
		}

		$attributes = wc_get_attribute_taxonomies();
		$req_attrs  = array();
		foreach ( $attributes as $id => $attribute ) {
			$filter = get_query_var( "filter_{$attribute->attribute_name}", '' );
			if ( ! empty( $filter ) ) {
				$req_attrs[] = array(
					'attribute' => "pa_{$attribute->attribute_name}",
					'operator'  => 'in',
					'slug'      => explode( ',', $filter ),
				);
			}
		}
		$request->set_param( 'attributes', $req_attrs );

		$min_price = get_query_var( 'min_price' );
		if ( ! empty( $min_price ) ) {
			$request->set_param( 'min_price', $min_price );
		}

		$max_price = get_query_var( 'max_price' );
		if ( ! empty( $max_price ) ) {
			$request->set_param( 'max_price', $max_price );
		}

		$request->set_param( '_locale', 'user' );

		return $request;
	}


	/**
	 * Gets the terms ids from a set of attributes slugs.
	 *
	 * @param array $filter The filter attribute for example:
	 * array(
	 *           'attribute' => "pa_attribute_1",
	 *           'operator'  => 'in',
	 *           'slug'      => array('slug_1', 'slug_2'),
	 *       ).
	 * @return array<int> The terms ids.
	 */
	public static function get_terms_ids( $filter ) {
		if ( ! isset( $filter['attribute'] ) || ! isset( $filter['slug'] ) || ! is_array( $filter['slug'] ) || empty( $filter['slug'] ) ) {
			return array();
		}

		$term_ids = array();
		foreach ( $filter['slug']as $slug ) {
			$term = get_term_by( 'slug', $slug, $filter['attribute'] );
			if ( is_object( $term ) ) {
				$term_ids[] = $term->term_id;
			}
		}

		return $term_ids;
	}

	/**
	 * Get a key for cache.
	 *
	 * @param string $prefix A prefix.
	 * @param string $count_attribute The attribute to get the count of.
	 * @param string $categories Category filters.
	 * @param string $filter_attributes Attribute filters.
	 * @param string $min_price Price filter.
	 * @param string $max_price Price filter.
	 * @return string The key.
	 */
	public static function get_transient_key( $prefix, $count_attribute, $categories, $filter_attributes, $min_price, $max_price ) {
		return $prefix . md5(
			wp_json_encode(
				array(
					'categories'        => $categories,
					'filter_attributes' => $filter_attributes,
					'count_attribute'   => $count_attribute,
					'min_price'         => $min_price,
					'max_price'         => $max_price,
				)
			)
		);
	}

	/**
	 * Check if an attribute is present in the request filter attribute array.
	 *
	 * @param array  $filters The filters.
	 * @param string $attribute the attribute to search.
	 * @return boolean True if present.
	 */
	public static function filter_attribute_has( $filters, $attribute ) {
		foreach ( $filters as $i => $atrr ) {
			if ( $atrr['attribute'] === $attribute ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Prepares a request for product query from the route and query params.
	 *
	 * @param boolean $calculate_price True to get the price range.
	 * @param boolean $attribute_count The attribute taxonomy name to calculate count.
	 * @return \WP_REST_Request The request with the filters extracted from query params and route.
	 */
	public function get_request( $calculate_price = false, $attribute_count = '' ): \WP_REST_Request {
		if ( get_queried_object() !== $this->queried_object || empty( $this->request ) ) {
			$this->queried_object = get_queried_object();
			$this->request        = self::get_request_from_query();
		}

		$request = clone $this->request;

		if ( $calculate_price ) {
			$request->set_param( 'calculate_price_range', true );
		}

		$calculate_attrs = array();
		if ( ! empty( $attribute_count ) ) {
			$calculate_attrs[] = array(
				'taxonomy'   => $attribute_count,
				'query_type' => 'or',
			);
		}

		$filter_attrs = $request->get_param( 'attributes' );
		foreach ( $filter_attrs as $i => $filter ) {
			if ( $filter['attribute'] !== $attribute_count ) {
				$calculate_attrs[] = array(
					'taxonomy'   => $filter['attribute'],
					'query_type' => 'or',
				);
			}
		}

		$request->set_param( 'calculate_attribute_counts', $calculate_attrs );

		return $request;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_filtered_price() {
		$request = $this->get_request( true );
		return $this->product_query_filters->get_filtered_price( $request );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_attribute_counts( $count_attribute ) {
		$request = $this->get_request( false, $count_attribute );

		$filter_attributes = $request->get_param( 'attributes', array() );

		$categories     = $request->get_param( 'category', array() );
		$where_category = '';
		if ( ! empty( $categories ) ) {
			$formatted_categories = implode( ',', array_map( 'intval', $categories ) );
			$where_category       = sprintf( 'AND rel.term_taxonomy_id IN (%1s) ', $formatted_categories );
		}

		$min_price       = $request->get_param( 'min_price' );
		$where_min_price = '';
		if ( ! empty( $min_price ) ) {
			$where_min_price = sprintf( 'AND meta.min_price >= %1s ', absint( $min_price ) );
		}

		$max_price       = $request->get_param( 'max_price' );
		$where_max_price = '';
		if ( ! empty( $max_price ) ) {
			$where_max_price = sprintf( 'AND meta.max_price <= %1s ', absint( $max_price ) );
		}

		$cache_key      = self::get_transient_key( 'shoophilters_get_attribute_counts_', $count_attribute, $categories, $filter_attributes, $min_price, $max_price );
		$cached_results = get_transient( $cache_key );
		if ( ! empty( $cached_results ) && defined( 'WP_DEBUG' ) && ! WP_DEBUG ) {
			return $cached_results;
		}

		global $wpdb;

		$where_taxonomy = $wpdb->prepare( 'taxonomy IN (%s)', $count_attribute );
		if ( ! empty( $filter_attributes ) && ! self::filter_attribute_has( $filter_attributes, $count_attribute ) ) {
			$term_ids = array();
			foreach ( $filter_attributes as $attr => $filter ) {
				$term_ids = array_merge( $term_ids, self::get_terms_ids( $filter ) );
			}
			$where_taxonomy = $wpdb->prepare( 'term_id IN (%1s)', implode( ',', $term_ids ) );
		}

		// phpcs:disable WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$counts = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT taxonomy, COUNT(DISTINCT product_attribute_lookup.product_or_parent_id) as term_count, product_attribute_lookup.term_id
				FROM {$wpdb->prefix}wc_product_attributes_lookup product_attribute_lookup
				INNER JOIN {$wpdb->posts} posts ON posts.ID = product_attribute_lookup.product_id
				INNER JOIN {$wpdb->prefix}wc_product_meta_lookup meta ON meta.product_id = product_attribute_lookup.product_id
				INNER JOIN {$wpdb->prefix}term_relationships rel ON rel.object_id = product_attribute_lookup.product_or_parent_id
				WHERE posts.post_type IN ('product', 'product_variation') AND posts.post_status = 'publish'
				%1s%2s%3sAND product_or_parent_id IN (
					SELECT DISTINCT product_or_parent_id FROM {$wpdb->prefix}wc_product_attributes_lookup WHERE {$where_taxonomy}
				)
				GROUP BY product_attribute_lookup.term_id
				HAVING taxonomy = %s;
				",
				$where_category,
				$where_min_price,
				$where_max_price,
				$count_attribute
			)
		);
		// phpcs:enable

		$results = array_map( 'absint', wp_list_pluck( $counts, 'term_count', 'term_id' ) );

		set_transient( $cache_key, $results, 24 * HOUR_IN_SECONDS );

		return $results;
	}
}
