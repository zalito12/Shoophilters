<?php
/**
 * Plugin Name:       Woofilters: Filters for Woocommerce
 * Description:       Blocks to extend default woocomerce filters fields.
 * Requires at least: 6.1
 * Requires PHP:      8.0
 * Version:           1.0.0
 * Author:            gon123
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       woofilters
 *
 * @package           woofilters
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WOOFILTERS_PLUGIN_FULL_NAME', 'Woofilters' );
define( 'WOOFILTERS_VERSION', '1.0.0' );
define( 'WOOFILTERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOOFILTERS_PATH_ADMIN', plugin_dir_path( __FILE__ ) . 'admin/' );

require WOOFILTERS_PATH . 'boot.php';


/**
 * The code that runs during plugin activation.
 */
function activate_woofilters() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woofilters-activator.php';
	News_By_Letter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_woofilters() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woofilters-deactivator.php';
	News_By_Letter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woofilters' );
register_deactivation_hook( __FILE__, 'deactivate_woofilters' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woofilters.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woofilters() {

	$plugin = new Woofilters();
	$plugin->run();
}
run_woofilters();



