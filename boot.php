<?php
/**
 * Autoloader for Shoophilters namespaces.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

spl_autoload_register(
	function( $class ) {
		if ( ! preg_match( '/^GonGarceIO\\\Shoophilters\\\/', $class ) ) {
			return;
		}

		$path = plugin_dir_path( __FILE__ );

		$file = str_replace(
			array( 'GonGarceIO\\Shoophilters\\', '\\', '_' ),
			array( '', DIRECTORY_SEPARATOR, '-' ),
			$class
		);

		$file = strtolower( $file );

		require_once trailingslashit( $path ) . trim( $file, '/' ) . '.php';
	}
);
