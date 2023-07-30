<?php

namespace GonGarceIO\WooFilters\Public;

use WooFilters_Settings;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woofilters
 * @subpackage Woofilters/public
 */
class Woofilters_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $woofilters    The ID of this plugin.
	 */
	private $woofilters;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $woofilters       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $woofilters, $version ) {

		$this->woofilters = $woofilters;
		$this->version    = $version;

	}

	/**
	 * Set ups all the actions and filters for public scope.
	 */
	public function load() {
		add_action( 'woocommerce_before_template_part', array( $this, 'woofilters_wc_before_template_part' ), 1 );

		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->woofilters, plugin_dir_url( __FILE__ ) . 'css/woofilters-public.css', array(), $this->version );
		wp_enqueue_style(
			'jquery-ui',
			'http://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.css',
			array(),
			'1.13.2'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->woofilters, plugin_dir_url( __FILE__ ) . 'js/woofilters-public.js', array( 'jquery', 'jquery-ui-slider', 'wp-i18n', 'wp-a11y' ), $this->version, false );

		wp_add_inline_script(
			$this->woofilters,
			'const WOOFILTERS = ' . wp_json_encode(
				array(
					'pagination' => WooFilters_Settings::get_option_with_default( WooFilters_Settings::OPTION_PAGINATION ),
					'selectors'  => WooFilters_Settings::get_option_with_default( WooFilters_Settings::OPTION_SELECTORS ),
				)
			),
			'before'
		);
	}

	/**
	 * Add custom showing results message for using infinite scroll.
	 *
	 * @param string $template_name The template to load.
	 */
	public function woofilters_wc_before_template_part( $template_name ) {
		if ( 'loop/result-count.php' === $template_name ) {
			add_filter( 'ngettext', array( $this, 'woofilters_load_more_products_count_additional' ), 1, 9999 );
			add_filter( 'ngettext_with_context', array( $this, 'woofilters_load_more_products_count_additional' ), 1, 9999 );
		}
	}

	/**
	 * Custom showing results message.
	 *
	 * @param string $gettext The text.
	 * @return string The text.
	 */
	public function woofilters_load_more_products_count_additional( $gettext ) {
		remove_filter( 'ngettext', array( $this, 'woofilters_load_more_products_count_additional' ), 1, 9999 );
		remove_filter( 'ngettext_with_context', array( $this, 'woofilters_load_more_products_count_additional' ), 1, 9999 );
		if ( class_exists( 'WC_Query' ) && method_exists( 'WC_Query', 'product_query' ) && function_exists( 'wc_get_loop_prop' ) ) {
				$total    = wc_get_loop_prop( 'total' );
				$per_page = wc_get_loop_prop( 'per_page' );
				$paged    = wc_get_loop_prop( 'current_page' );
				$first    = ( $per_page * $paged ) - $per_page + 1;
				$last     = min( $total, $per_page * $paged );
		} else {
			global $wp_query;
			$paged    = max( 1, $wp_query->get( 'paged' ) );
			$per_page = $wp_query->get( 'posts_per_page' );
			$total    = $wp_query->found_posts;
			$first    = ( $per_page * $paged ) - $per_page + 1;
			$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );
		}

		echo '<span class="woofilters-result-count" style="display: none;" data-total="' . esc_attr( $total ) . '" data-first="' . esc_attr( $first ) . '" data-last="' . esc_attr( $last ) . '">';
		if ( 1 === $total ) {
			esc_html_e( 'Showing the single result', 'woocommerce' );
		} elseif ( $total <= $per_page || -1 === $per_page || $last >= $total ) {
			/* translators: %d: total results */
			echo esc_html( sprintf( _n( 'Showing all %d result', 'Showing all %d results', $total, 'woocommerce' ), $total ) );
		} else {
			/* translators: 1: first result 2: last result 3: total results */
			echo esc_html( sprintf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'woocommerce' ), 1, -1, $total ) );
		}
		echo '</span>';

		return $gettext;
	}
}
