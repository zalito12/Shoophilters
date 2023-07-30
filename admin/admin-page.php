<?php

namespace GonGarceIO\Shoophilters\Admin;

/**
 * Abstract class to extends in adming pages.
 */
abstract class Admin_Page {


	/**
	 * Path to the admin page templates.
	 *
	 * @var string
	 */
	private $page_template;

	/**
	 * Path to the admin page templates.
	 *
	 * @var string
	 */
	private $templates_path;

	/**
	 * Constructor.
	 *
	 * @param string $page_template_name The template name to render when page loads.
	 * @param string $templates_subpath  templates subpath for this page starting.
	 */
	public function __construct( $page_template_name, $templates_subpath = '' ) {
		$this->page_template  = $page_template_name;
		$this->templates_path = SHOOPHILTERS_PATH_ADMIN . 'templates/' . ( empty( $templates_subpath ) ? '' : trim( $templates_subpath, '/' ) . '/' );
	}

	/**
	 * Callback to be called before page lodas on page-{$hook}.
	 */
	public function before_load() {
		$this->do_actions();

		$this->show_messages();

		$this->configure();
	}


	/**
	 * Configure the admin page using the Settings API.
	 */
	abstract public function configure();

	/**
	 * Get the capability required to view the admin page.
	 *
	 * @return string
	 */
	abstract public static function get_capability();

	/**
	 * Get the title of the admin page in the WordPress admin menu.
	 *
	 * @return string
	 */
	abstract public static function get_menu_title();

	/**
	 * Get the title of the admin page.
	 *
	 * @return string
	 */
	abstract public static function get_page_title();

	/**
	 * Get the parent slug of the admin page.
	 *
	 * @return string
	 */
	abstract public static function get_parent_slug();

	/**
	 * Get the slug used by the admin page.
	 *
	 * @return string
	 */
	abstract public static function get_slug();

	/**
	 * Should return the list of actions that can be permormed on this page.
	 * Reffers to form submission.
	 *
	 * @return string[]
	 */
	abstract protected function get_available_actions();

	/**
	 * This will be called when an action is present on request and this action is
	 * available in this page. It will prevent the page to be rendered. Child class
	 * must implement this with the appropiate logic.
	 *
	 * @param string $action The action to be performed.
	 *
	 * @return string[]
	 */
	abstract protected function do_action( $action);

	/**
	 * Render the plugin's admin page.
	 */
	public function render_page() {
		$this->render_template( $this->page_template );
	}

	/**
	 * Renders the given template if it's readable.
	 *
	 * @param string $template_name The template name to load.
	 */
	public function render_template( $template_name ) {
		$template_path = $this->templates_path . $template_name . '.php';
		if ( ! is_readable( $template_path ) ) {
			return;
		}

		include $template_path;
	}

	/**
	 * Check request for an available action and perform it if found.
	 * It also checks for nonce token and right permissions.
	 *
	 * @return bool True if valid action found, false otherwise.
	 */
	private function do_actions() {
		$action = $this->get_page_action();
		if ( ! empty( $this->get_available_actions() ) && ! empty( $action ) && in_array( $action, $this->get_available_actions(), true ) ) {
			$this->do_action( $action );
			return true;
		}

		return false;
	}

	/**
	 * Check query params for message information and load an admin notice if found.
	 *
     * @phpcs:disable WordPress.Security
	 */
	private function show_messages() {
		if ( isset( $_GET['message'] ) && isset( $_GET['result'] ) ) {
			$result  = $_GET['result'];
			$message = $_GET['message'];

			\Admin_Notice::display( $message, $result );
		}
	}

	/**
	 * Gets the action from the request. It checks that request has a correct nonce token
	 * and user has capabilities.
	 *
	 * @return string|null The action name to be performed or null if no action or no nonce token
	 */
	private function get_page_action() {
		if ( ! isset( $_REQUEST['action'] ) || empty( $_REQUEST['action'] ) ) {
			return null;
		}

		$action = $_REQUEST['action'];  // phpcs:ignore WordPress.Security
		if ( ! check_admin_referer( $action ) ) {
			return null;
		}

		if ( ! current_user_can( $this->get_capability() ) ) {
			return null;
		}

		return sanitize_key( $action );
	}
}
