<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Woofilters
 * @subpackage Woofilters/includes
 */

use GonGarceIO\WooFilters\Admin\Woofilters_Admin;
use GonGarceIO\WooFilters\Includes\Woofilters_Block_Loader;
use GonGarceIO\WooFilters\Includes\Woofilters_I18n;
use GonGarceIO\WooFilters\Public\Woofilters_Public;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woofilters
 * @subpackage Woofilters/includes
 * @author     Your Name <email@example.com>
 */
class Woofilters {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $woofilters    The string used to uniquely identify this plugin.
	 */
	protected $woofilters;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WOOFILTERS_VERSION' ) ) {
			$this->version = WOOFILTERS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->woofilters = 'woofilters';

		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woofilters_Loader. Orchestrates the hooks of the plugin.
	 * - Woofilters_i18n. Defines internationalization functionality.
	 * - Woofilters_Admin. Defines all hooks for the admin area.
	 * - Woofilters_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Global settings utilities.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/woofilters-settings.php';
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$plugin_i18n = new Woofilters_I18n();
		$plugin_i18n->load();

		$plugin_public = new Woofilters_Public( $this->get_woofilters(), $this->get_version() );
		$plugin_public->load();

		if ( is_admin() ) {
			$plugin_admin = new Woofilters_Admin( $this->get_woofilters(), $this->get_version() );
			$plugin_admin->load();
		}

		$block_loader = new Woofilters_Block_Loader();
		$block_loader->load();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_woofilters() {
		return $this->woofilters;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
