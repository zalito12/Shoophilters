<?php

namespace GonGarceIO\WooFilters\Admin;

use GonGarceIO\WooFilters\Admin\Admin_Page;

/**
 * Factory for admin pages.
 */
class WooFilters_Page_Factory {

	/**
	 * Saves the current page reference for later use.
	 *
	 * @var $page The current page reference.
	 */
	public static Admin_Page $page;

	/**
	 * Create admin pages and calls it before load method.
	 *
	 * @param Admin_Page|string $page An admin page instance or a string with the name of the admin page class.
	 */
	public static function load( $page ) {
		if ( gettype( $page ) === 'string' ) {
			self::$page = new $page();
			self::$page->before_load();
		} else {
			self::$page = $page;
			self::$page->before_load();
		}
	}

	/**
	 * Renders the admin page.
	 */
	public static function render() {
		self::$page->render_page();
	}
}
