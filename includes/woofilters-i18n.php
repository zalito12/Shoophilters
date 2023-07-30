<?php

namespace GonGarceIO\WooFilters\Includes;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woofilters
 * @subpackage Woofilters/includes
 * @author     Your Name <email@example.com>
 */
class Woofilters_I18n {

	/**
	 * Set ups all the actions and filters for admin scope.
	 */
	public function load() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woofilters',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
