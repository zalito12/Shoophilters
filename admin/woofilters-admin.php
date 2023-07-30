<?php

namespace GonGarceIO\WooFilters\Admin;

use GonGarceIO\WooFilters\Admin\Pages\WooFilters_Page_Settings;
use WooFilters_Settings;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woofilters
 * @subpackage Woofilters/admin
 * @author     Your Name <email@example.com>
 */
class Woofilters_Admin {

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
	 * @param      string $woofilters       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $woofilters, $version ) {

		$this->woofilters = $woofilters;
		$this->version    = $version;
	}

	/**
	 * Set ups all the actions and filters for admin scope.
	 */
	public function load() {
		$this->register_settings();

		add_action( 'admin_menu', array( $this, 'create_menu' ) );

		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->woofilters, plugin_dir_url( __FILE__ ) . 'css/woofilters-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->woofilters, plugin_dir_url( __FILE__ ) . 'js/woofilters-admin.js', array(), $this->version, false );
	}


	/**
	 * Creates plugin admin menu
	 *
	 * @since 1.0.0
	 */
	public function create_menu() {
		$settings_page = new WooFilters_Page_Settings();

		$hook_page = add_options_page(
			$settings_page::get_page_title(),
			$settings_page::get_menu_title(),
			$settings_page::get_capability(),
			$settings_page::get_slug(),
			array( 'GonGarceIO\WooFilters\Admin\WooFilters_Page_Factory', 'render' ),
			50
		);
		add_action(
			"load-$hook_page",
			function() use ( $settings_page ) {
				WooFilters_Page_Factory::load( $settings_page );
			}
		);
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		// Register settings array for pagination section.
		register_setting( WooFilters_Page_Settings::get_slug(), WooFilters_Settings::OPTION_PAGINATION );
		// Register settings array for section subscribe.
		register_setting( WooFilters_Page_Settings::get_slug(), WooFilters_Settings::OPTION_SELECTORS );
	}
}
