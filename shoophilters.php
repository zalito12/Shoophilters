<?php
/**
 * Plugin Name:       Shoophilters: Filters for Woocommerce
 * Description:       Blocks to extend default woocomerce filters fields.
 * Plugin URI:        https://
 * Requires at least: 6.1
 * Requires PHP:      8.0
 * Version:           1.0.0
 * Author:            GonGarce
 * Author URI:        https://gongarce.io
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shoophilters
 * Domain Path:       /languages/
 *
 * @package Shoophilters
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SHOOPHILTERS_PLUGIN_FULL_NAME', 'Shoophilters' );
define( 'SHOOPHILTERS_VERSION', '1.0.0' );
define( 'SHOOPHILTERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHOOPHILTERS_PATH_ADMIN', plugin_dir_path( __FILE__ ) . 'admin/' );

require SHOOPHILTERS_PATH . 'boot.php';


/**
 * The code that runs during plugin activation.
 */
function activate_shoophilters() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shoophilters-activator.php';
	Shoophilters_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_shoophilters() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shoophilters-deactivator.php';
	Shoophilters_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_shoophilters' );
register_deactivation_hook( __FILE__, 'deactivate_shoophilters' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-shoophilters.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_shoophilters() {

	$plugin = new Shoophilters();
	$plugin->run();
}
run_shoophilters();



