<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */

use GonGarceIO\Shoophilters\Admin\Shoophilters_Admin;
use GonGarceIO\Shoophilters\Includes\Shoophilters_Block_Loader;
use GonGarceIO\Shoophilters\Includes\Shoophilters_I18n;
use GonGarceIO\Shoophilters\Public\Shoophilters_Public;

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
 * @package    Shoophilters
 * @subpackage Shoophilters/includes
 * @author     Your Name <email@example.com>
 */
class Shoophilters {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $shoophilters    The string used to uniquely identify this plugin.
	 */
	protected $shoophilters;

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
		if ( defined( 'SHOOPHILTERS_VERSION' ) ) {
			$this->version = SHOOPHILTERS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->shoophilters = 'shoophilters';

		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Shoophilters_Loader. Orchestrates the hooks of the plugin.
	 * - Shoophilters_i18n. Defines internationalization functionality.
	 * - Shoophilters_Admin. Defines all hooks for the admin area.
	 * - Shoophilters_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shoophilters-settings.php';
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$plugin_i18n = new Shoophilters_I18n();
		$plugin_i18n->load();

		$plugin_public = new Shoophilters_Public( $this->get_shoophilters(), $this->get_version() );
		$plugin_public->load();

		if ( is_admin() ) {
			$plugin_admin = new Shoophilters_Admin( $this->get_shoophilters(), $this->get_version() );
			$plugin_admin->load();
		}

		$block_loader = new Shoophilters_Block_Loader();
		$block_loader->load();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_shoophilters() {
		return $this->shoophilters;
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
