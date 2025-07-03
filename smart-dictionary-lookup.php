<?php
/*
Plugin Name: Smart Dictionary Lookup
Plugin URI: https://wordpress.org/plugins/smart-dictionary-lookup
Description: Smart, instant word definitions with a beautiful popup when users double-click text. Fully customizable, lightweight, and WordPress-friendly.
Version: 1.0.0
Author: Sabbir Noyon
Author URI: https://profiles.wordpress.org/sabbirnoyon/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: smart-dictionary-lookup
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin core classes
require_once plugin_dir_path( __FILE__ ) . 'includes/class-sdl-core.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-sdl-admin.php';

/**
 * Main plugin class.
 * 
 * Initializes the Smart Dictionary Lookup plugin.
 */
final class Smart_Dictionary_Lookup {

	public function __construct() {
		$this->define_constants();
		
		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );

		// Register activation and deactivation hooks.
		register_activation_hook( __FILE__, [ $this, 'on_activation' ] );
		register_deactivation_hook( __FILE__, [ $this, 'on_deactivation' ] );
	}

	
	 // Define plugin constants.
	private function define_constants() {
		define( 'SDL_VERSION', '1.0.0' );
		define( 'SDL_FILE', __FILE__ );
		define( 'SDL_PATH', plugin_dir_path( __FILE__ ) );
		define( 'SDL_URL', plugin_dir_url( __FILE__ ) );
		define( 'SDL_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/' );
	}

	// Initialize plugin core classes
	public function init_plugin() {
		if ( is_admin() ) {
			new SDL_Admin(); // Admin area functionality
		}

		new SDL_Core(); // Frontend functionality
	}

	// Plugin activation callback
	public function on_activation() {
		// For future, when need
	}

	// Plugin deactivation callback
	public function on_deactivation() {
		// For future, when need
	}
}

// Boot the plugin
new Smart_Dictionary_Lookup();
