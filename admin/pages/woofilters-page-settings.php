<?php

namespace GonGarceIO\WooFilters\Admin\Pages;

use GonGarceIO\WooFilters\Admin\Admin_Page;

/**
 * Page for the plugin settings.
 */
class WooFilters_Page_Settings extends Admin_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( 'template-settings', 'settings' );
	}

	/**
	 * Configure the admin page using the Settings API.
	 */
	public function configure() {
		add_settings_section(
			self::get_slug() . '-pagination',
			__( 'Pagination options', 'woofilters' ),
			array(),
			self::get_slug()
		);
		add_settings_field(
			self::get_slug() . '-pagination-type',
			__( 'Pagination type', 'woofilters' ),
			array( $this, 'render_option_field' ),
			self::get_slug(),
			self::get_slug() . '-pagination',
			array(
				'template' => 'setting-pagination',
			)
		);
		add_settings_field(
			self::get_slug() . '-scroll-to',
			__( 'Scroll to this position after loading a new page', 'woofilters' ),
			array( $this, 'render_option_field' ),
			self::get_slug(),
			self::get_slug() . '-pagination',
			array(
				'template' => 'setting-scroll-to',
			)
		);

		add_settings_section(
			self::get_slug() . '-selectors',
			__( 'Element selectors', 'woofilters' ),
			array(),
			self::get_slug()
		);
		add_settings_field(
			self::get_slug() . '-element-result-count',
			__( 'Result count element', 'woofilters' ),
			array( $this, 'render_option_field' ),
			self::get_slug(),
			self::get_slug() . '-selectors',
			array(
				'template' => 'settings-selector-result-count',
			)
		);
		add_settings_field(
			self::get_slug() . '-element-content',
			__( 'Result count element', 'woofilters' ),
			array( $this, 'render_option_field' ),
			self::get_slug(),
			self::get_slug() . '-selectors',
			array(
				'template' => 'settings-selector-content',
			)
		);
		add_settings_field(
			self::get_slug() . '-element-products',
			__( 'Products element', 'woofilters' ),
			array( $this, 'render_option_field' ),
			self::get_slug(),
			self::get_slug() . '-selectors',
			array(
				'template' => 'settings-selector-products',
			)
		);
		add_settings_field(
			self::get_slug() . '-element-pagination',
			__( 'Pagination element', 'woofilters' ),
			array( $this, 'render_option_field' ),
			self::get_slug(),
			self::get_slug() . '-selectors',
			array(
				'template' => 'settings-selector-pagination',
			)
		);
		add_settings_field(
			self::get_slug() . '-element-page',
			__( 'Page item element', 'woofilters' ),
			array( $this, 'render_option_field' ),
			self::get_slug(),
			self::get_slug() . '-selectors',
			array(
				'template' => 'settings-selector-page',
			)
		);
	}

	/**
	 * Renders the option field.
	 *
	 * @param array $args The args to create option field.
	 */
	public function render_option_field( $args ) {
		$this->render_template( $args['template'] );
	}

	/**
	 * Get the capability required to view the admin page.
	 *
	 * @return string
	 */
	public static function get_capability() {
		return 'publish_pages';
	}

	/**
	 * Get the title of the admin page in the WordPress admin menu.
	 *
	 * @return string
	 */
	public static function get_menu_title() {
		return WOOFILTERS_PLUGIN_FULL_NAME;
	}

	/**
	 * Get the title of the admin page.
	 *
	 * @return string
	 */
	public static function get_page_title() {
		return WOOFILTERS_PLUGIN_FULL_NAME . ': ' . __( 'Settings' );
	}

	/**
	 * Get the parent slug of the admin page.
	 *
	 * @return string
	 */
	public static function get_parent_slug() {
		return 'Settings';
	}

	/**
	 * Get the slug used by the admin page.
	 *
	 * @return string
	 */
	public static function get_slug() {
		return 'woofilters-settings';
	}

	/**
	 * Should return the list of actions that can be permormed on this page.
	 * Reffers to form submission.
	 *
	 * @return string[]
	 */
	protected function get_available_actions() {
		return array();
	}

	/**
	 * This will be called when an action is present on request and this action is
	 * available in this page. It will prevent the page to be rendered. Child class
	 * must implement this with the appropiate logic.
	 *
	 * @param string $action The action to be performed.
	 */
	protected function do_action( $action ) {
		switch ( $action ) {
			default:
				wp_die( 'Nothing to do here' );
		}
	}
}
